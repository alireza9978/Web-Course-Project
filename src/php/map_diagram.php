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
echo '<p style="color:' . $color . ';">Your chart circle color is:' . $color . '</p>'; ?>

<p>
    Your chart caption is: "<?php echo $_POST["caption"]; ?>"
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

</body>
</html>