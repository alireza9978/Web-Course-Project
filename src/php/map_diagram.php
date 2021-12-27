<html lang="en">
<body>

<!-- read xlsx file with php and save in variables-->
<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;

$reader = new Xls();

$spreadsheet_cities = $reader->load("../../raw/cities.xls");
$spreadsheet_states = $reader->load("../../raw/coordinates_states.xls");
$cities_array = [];
$states_array = [];
try {
    $sheetData = $spreadsheet_cities->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $cities_array[$t[0]] = [$t[1], $t[2]];
    }

    $sheetData = $spreadsheet_states->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $states_array[$t[0]] = [$t[1], $t[2], $t[3]];
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
            $temp = $states_array[$key];
            echo "id=$key $temp[0] $temp[1] $temp[2], value=$val<br/>";
        }
    } elseif ($chart_type == 2) {
        global $cities_array;
        foreach ($data as $key => $val) {
            $temp = $cities_array[$key];
            echo "id=$key, state_name=$temp[0] state_id=$temp[1] value=$val<br/>";
        }
    }
    return true;
}

?>

Welcome <br>

<!-- chart type -->
<p>
    <?php
    $chartType = $_POST["chart_type"];
    echo "Your chart type is:" . $chartType;
    ?>
</p>

<!-- chart color -->
<?php
$color = $_POST["color"];
list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
echo '<p style="color:' . $color . ';">Your chart circle color is: ' . $color . '</br>';
echo 'Your chart circle color is: R:' . $r . ' G:' . $g . ' B:' . $b . '</p>';
?>

<!-- chart caption -->
<p>
    <?php
    $chart_caption = $_POST["caption"];
    ?>
    Your chart caption is: "<?php echo $chart_caption; ?>"
</p>

<!-- chart data -->
<p>
    <?php
    $chartData = $_POST["chart_data"];
    $chartDataValidation = validate_data($chartData, $chartType);
    ?>
    Your chart data is: <?php echo $chartData; ?>
    <br>
    Your chart data is: <?php echo get_validation_str($chartDataValidation); ?>
</p>

<!-- chart path -->
<p>
    <?php
    $outputPath = $_POST["output_path"];
    $outputFile = $_POST["output_name"];
    if ($outputFile != null and $outputPath != null) {
        if (is_dir($outputPath)) {
            if (!file_exists($outputPath)) {
                mkdir($outputPath, 0777, true);
            }
            $outputFile = $outputPath . "/" . $outputFile;
        }
    } else {
        $outputFile = "no where";
    }
    ?>
    Your Chart Saved in = <?php echo $outputFile; ?>

</p>

<?php
$max_circle_diameter = 100;
$min_circle_diameter = 50;
$circle_diameter_range = $max_circle_diameter - $min_circle_diameter;
$main_image_width = 2560;
$main_image_height = 2560;
$caption_height = 250;

////create a white image
if ($chart_caption == null) {
    $final_image = imagecreatetruecolor($main_image_width, $main_image_height);
} else {
    $final_image = imagecreatetruecolor($main_image_width, $main_image_height + $caption_height);
}
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
$circle_color = imagecolorallocatealpha($im, $r, $g, $b, 32);

// Draw circle in center of states
$data = json_decode($chartData);
if ($chartType == 1) {
    $max_value = -1;
    $min_value = 100000;
    foreach ($data as $key => $temp_value) {
        if ($temp_value > $max_value) {
            $max_value = $temp_value;
        }
        if ($temp_value < $min_value) {
            $min_value = $temp_value;
        }
    }
    $values_different = $max_value - $min_value;
    foreach ($data as $key => $val) {
        $temp = $states_array[$key];
        $temp_value = $val;
        $temp_value = $temp_value - $min_value;
        $temp_value = $temp_value / $values_different;
        $temp_value = $circle_diameter_range * $temp_value;
        $temp_value = $temp_value + $min_circle_diameter;
        imagefilledellipse($im, $temp[1], $temp[2], $temp_value, $temp_value, $circle_color);
    }
} elseif ($chartType == 2) {
    $temp_city_state_array = [];
    foreach ($data as $key => $temp_value) {
        $temp_city = $cities_array[$key];
        if ($temp_city_state_array[$temp_city[1]] == null) {
            $temp_city_state_array[$temp_city[1]] = $temp_value;
        } else {
            $temp_city_state_array[$temp_city[1]] += $temp_value;
        }
    }
    $max_value = -1;
    $min_value = 100000;
    foreach ($temp_city_state_array as $key => $temp_value) {
        if ($temp_value > $max_value) {
            $max_value = $temp_value;
        }
        if ($temp_value < $min_value) {
            $min_value = $temp_value;
        }
    }
    $values_different = $max_value - $min_value;
    foreach ($temp_city_state_array as $key => $val) {
        $temp = $states_array[$key];
        $temp_value = $val;
        $temp_value = $temp_value - $min_value;
        $temp_value = $temp_value / $values_different;
        $temp_value = $circle_diameter_range * $temp_value;
        $temp_value = $temp_value + $min_circle_diameter;
        imagefilledellipse($im, $temp[1], $temp[2], $temp_value, $temp_value, $circle_color);
    }
}

// Copy and merge
imagecopymerge($final_image, $im, 0, 0, 0, 0, $main_image_width, $main_image_height, 100);

////add caption to final image
// Replace path by your own font path
$font = '../fonts/arial.ttf';

// Add the text
if ($chart_caption != null) {
    imagettftext($final_image, 50, 0, $main_image_width / 2, $main_image_height + ($caption_height / 2), $black, $font, $chart_caption);
}

// Save the image to a file.
// output the picture
if ($outputFile !== "no where") {
    imagepng($final_image, $outputFile);
}
imagepng($final_image, $output_path);
// Destroy the image handler.
imagedestroy($im);
imagedestroy($final_image);
?>
<img src="<?php echo $output_path; ?>" alt="iran map" width="500" height="500">

</body>
</html>