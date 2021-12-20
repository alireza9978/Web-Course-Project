<html>
<body>

<!-- read xlsx file with php and save in variables-->
<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;

$reader = new Xls();

$spreadsheet_cities = $reader->load("../../raw/cities.xls");
$spreadsheet_states = $reader->load("../../raw/states.xls");
$cities_array = [];
$states_array = [];
try {
    $sheetData = $spreadsheet_cities->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $cities_array[$t[0]] = $t[1];
    }

    $sheetData = $spreadsheet_states->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $states_array[$t[0]] = $t[1];
    }
} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
    echo "error";
}

function get_validation_str($bool = false): string
{
    if ($bool) {
        return "valid";
    } else {
        return "invalid";
    }
}

function validate_json($str = NULL): bool
{
    if (is_string($str)) {
        @json_decode($str);
        return (json_last_error() === JSON_ERROR_NONE);
    }
    return false;
}

function validate_data($str = NULL, $chart_type = NULL): bool
{
    $strValidation = validate_json($str);
    if (!$strValidation) {
        return false;
    }
    $data = json_decode($str);
    if ($chart_type == 1) {
        global $states_array;
        foreach ($data as $key => $val) {
            echo "$states_array[$key] = $key is $val<br/>";
        }
    } elseif ($chart_type == 2) {
        global $cities_array;
        foreach ($data as $key => $val) {
            echo "$cities_array[$key] = $key is $val<br/>";
        }
    }
    return true;
}

?>

Welcome <br>
<p>
    <?php
    $chartType = $_POST["chart_type"];
    echo "Your chart type is:" . $chartType;
    ?>

</p>

<?php
$color = $_POST["color"];
list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
echo '<p style="color:' . $color . ';">Your chart circle color is: ' . $color . '</br>';
echo 'Your chart circle color is: R:' . $r . ' G:' . $g . ' B:' . $b . '</p>';
?>

<p>
    <?php
    $chart_caption = $_POST["caption"];
    ?>
    Your chart caption is: "<?php echo $chart_caption; ?>"
</p>

<p>
    <?php
    $chartData = $_POST["chart_data"];
    $chartDataValidation = validate_data($chartData, $chartType);
    ?>
    Your chart data is: <?php echo $chartData; ?>
    <br>
    Your chart data is: <?php echo get_validation_str($chartDataValidation); ?>
</p>

<?php
$circle_diameter = 100;
$main_image_width = 2560;
$main_image_height = 2560;
$caption_height = 250;

////create a white image
$final_image = imagecreatetruecolor($main_image_width, $main_image_height + $caption_height);
// Create some colors
$white = imagecolorallocate($final_image, 255, 255, 255);
$grey = imagecolorallocate($final_image, 128, 128, 128);
$black = imagecolorallocate($final_image, 0, 0, 0);
// fill final image with white
imagefill($final_image, 0, 0, $white);

////load map and add circle
$image_path = "../images/map-scaled.png";
$output_path = "../images/out.png";
$im = imagecreatefrompng($image_path);
// Create a colour.
$circle_color = imagecolorallocate($im, $r, $g, $b);
// Draw a circle in the middle of the image.
imagefilledellipse($im, $main_image_height / 2, $main_image_height / 2, $circle_diameter, $circle_diameter, $circle_color);
// Copy and merge
imagecopymerge($final_image, $im, 0, 0, 0, 0, $main_image_width, $main_image_height, 100);

////add caption to final image
// Replace path by your own font path
$font = '../fonts/arial.ttf';

// Add the text
imagettftext($final_image, 50, 0, $main_image_width / 2, $main_image_height + ($caption_height / 2), $black, $font, $chart_caption);

// Save the image to a file.
// output the picture
imagepng($final_image, $output_path);
// Destroy the image handler.
imagedestroy($im);
imagedestroy($final_image);
?>
<img src="<?php echo $output_path ?>" alt="iran map" width="500" height="500">


</body>
</html>