<html lang="en">
<body>

<!-- read xlsx file with php and save in variables-->
<?php

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;

$reader = new Xls();

$spreadsheet_cities = $reader->load("../../raw/cities.xls");
$spreadsheet_coordinates_cities = $reader->load("../../raw/coordinates_cities.xls");
$spreadsheet_states = $reader->load("../../raw/coordinates_states.xls");
$spreadsheet_world = $reader->load("../../raw/world.xls");
$coordinates_cities_array = [];
$cities_array = [];
$states_array = [];
$world_array = [];
try {
    $sheetData = $spreadsheet_cities->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $cities_array[$t[0]] = [$t[1], $t[2]];
    }

    $sheetData = $spreadsheet_coordinates_cities->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $coordinates_cities_array[$t[1]] = [$t[2], $t[0], $t[4], $t[5]];
    }

    $sheetData = $spreadsheet_states->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $states_array[$t[0]] = [$t[1], $t[2], $t[3]];
    }

    $sheetData = $spreadsheet_world->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $world_array[$t[0]] = [$t[1], $t[2], $t[3]];
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
            if ($temp == null) {
                return false;
            }
//            echo "id=$key $temp[0] $temp[1] $temp[2], value=$val<br/>";
        }
    } elseif ($chart_type == 2) {
        global $cities_array;
        foreach ($data as $key => $val) {
            $temp = $cities_array[$key];
            if ($temp == null) {
                return false;
            }
//            echo "id=$key, state_name=$temp[0] state_id=$temp[1] value=$val<br/>";
        }
    } elseif ($chart_type == 3) {
        global $world_array;
        foreach ($data as $key => $val) {
            $temp = $world_array[$key];
            if ($temp == null) {
                return false;
            }
//            echo "id=$key, country_name=$temp[0] value=$val<br/>";
//            echo "x=$temp[1], y=$temp[2] <br/>";
        }
    }
    return true;
}

?>

<!-- handle post data -->
<?php
$chartType = $_POST["chart_type"];

$color = $_POST["color"];
list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");

$chart_caption = $_POST["caption"];

$chart_sum_up = $_POST["sum_up"];
if ($chart_sum_up != null) {
    $chart_sum_up = true;
} else {
    $chart_sum_up = false;
}

$chartData = $_POST["chart_data"];
$chartDataValidation = validate_data($chartData, $chartType);

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

<!-- image processing section -->
<?php
if ($chartDataValidation) {

    function get_html_image_size($chartType): array
    {
        $html_image_width = 500;
        $html_image_height = 500;
        if ($chartType == 1 || $chartType == 2) {
            $html_image_width = 500;
        } elseif ($chartType == 3) {
            $html_image_width = 600;
        }
        if ($chartType == 1 || $chartType == 2) {
            $html_image_height = 500;
        } elseif ($chartType == 3) {
            $html_image_height = 400;
        }
        return array($html_image_width, $html_image_height);
    }

    function get_final_image_size($chartType): array
    {
        $main_image_width = null;
        $main_image_height = null;
        if ($chartType == 1 || $chartType == 2) {
            $main_image_width = 2560;
            $main_image_height = 2560;
        } elseif ($chartType == 3) {
            $main_image_width = 3840;
            $main_image_height = 2200;
        }
        return array($main_image_width, $main_image_height);
    }

    function find_min_and_max($data): array
    {
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
        return array($min_value, $max_value);
    }

    function draw_circles_on_image($data, $place_array, $min_max_value, $image, $color, $circle_diameter_range, $min_circle_diameter, $chartType)
    {
        $min_value = $min_max_value[0];
        $max_value = $min_max_value[1];
        $values_different = $max_value - $min_value;
        foreach ($data as $key => $val) {
            $temp = $place_array[$key];
            $temp_value = $val;
            $temp_value = $temp_value - $min_value;
            $temp_value = $temp_value / $values_different;
            $temp_value = $circle_diameter_range * $temp_value;
            $temp_value = $temp_value + $min_circle_diameter;
            if ($chartType == 2){
                imagefilledellipse($image, $temp[3], $temp[2], $temp_value, $temp_value, $color);
            }else{
                imagefilledellipse($image, $temp[1], $temp[2], $temp_value, $temp_value, $color);
            }
        }
    }

    $max_circle_diameter = 60;
    $min_circle_diameter = 20;
    $circle_diameter_range = $max_circle_diameter - $min_circle_diameter;
    $caption_height = 200;
    $temp = get_final_image_size($chartType);
    $main_image_width = $temp[0];
    $main_image_height = $temp[1];

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
    imagefill($final_image, 0, 0, $black);

////load map and add circle

    $image_path = null;
    if ($chartType == 1 || $chartType == 2) {
        $image_path = "../images/map-scaled.png";
    } elseif ($chartType == 3) {
        $image_path = "../images/world-map.png";
    }
    $output_path = "../images/out.png";
    $im = imagecreatefrompng($image_path);
// Create a colour.
    $circle_color = imagecolorallocatealpha($im, $r, $g, $b, 32);

// Draw circle in center of states
    $data = json_decode($chartData);
    if ($chartType == 1) {
        draw_circles_on_image($data, $states_array, find_min_and_max($data), $im, $circle_color, $circle_diameter_range, $min_circle_diameter, $chartType);
    } elseif ($chartType == 2) {
        if ($chart_sum_up) {
            $temp_city_state_array = [];
            foreach ($data as $key => $temp_value) {
                $temp_city = $cities_array[$key];
                if ($temp_city_state_array[$temp_city[1]] == null) {
                    $temp_city_state_array[$temp_city[1]] = $temp_value;
                } else {
                    $temp_city_state_array[$temp_city[1]] += $temp_value;
                }
            }
            draw_circles_on_image($temp_city_state_array, $states_array, find_min_and_max($temp_city_state_array), $im, $circle_color, $circle_diameter_range, $min_circle_diameter, 1);
        } else {
            draw_circles_on_image($data, $coordinates_cities_array, find_min_and_max($data), $im, $circle_color, $circle_diameter_range, $min_circle_diameter, $chartType);
        }
    } elseif ($chartType == 3) {
        draw_circles_on_image($data, $world_array, find_min_and_max($data), $im, $circle_color, $circle_diameter_range, $min_circle_diameter, $chartType);
    }

// Copy and merge
    imagecopymerge($final_image, $im, 0, 0, 0, 0, $main_image_width, $main_image_height, 100);

////add caption to final image
// Replace path by your own font path
//$font = '../fonts/arial.ttf';
    $font = 'C:\Users\Alireza\PhpstormProjects\Web-Course-Project\src\fonts\arial.ttf';
// Add the text
    if ($chart_caption != null) {
        imagettftext($final_image, 50, 0, $main_image_width / 2, $main_image_height + ($caption_height / 2), $white, $font, $chart_caption);
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
}
?>

Welcome <br>

<!-- chart type -->
<p>
    Your chart type is: <?php echo $chartType; ?>
</p>

<!-- chart color -->
<p style="color:<?php echo $color; ?>">
    Your chart circle color is: <?php echo $color; ?>
    <br>
    Your chart circle color is: <?php echo 'R:' . $r . ' G:' . $g . ' B:' . $b; ?>
</p>

<!-- chart caption -->
<p>
    Your chart caption is: "<?php echo $chart_caption; ?>"
</p>

<!-- sum up -->
<p>
    Your chart cities sum up state : "<?php echo $chart_sum_up; ?>"
</p>

<!-- chart data -->
<p>
    Your chart data is: <?php echo $chartData; ?>
    <br>
    Your chart data is: <?php echo get_validation_str($chartDataValidation); ?>
</p>

<!-- chart path -->
<p>
    Your Chart Saved in = <?php echo $outputFile; ?>
</p>

<?php
$html_image_size = get_html_image_size($chartType);
if ($chartDataValidation) {
    echo '<img src=' . $output_path . ' alt= "iran map" width=' . $html_image_size[0] . 'height=' . $html_image_size[1] . '> ';
} else {
    echo "input data is invalid";
}

?>


</body>
</html>