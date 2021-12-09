<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new Xls();

$spreadsheet = $reader->load("../raw/cities.xls");

try {
    $d = $spreadsheet->getSheet(0)->toArray();
    echo count($d);
    echo "<br>";
    $sheetData = $spreadsheet->getActiveSheet()->toArray();
    $i=1;
    unset($sheetData[0]);
    foreach ($sheetData as $t) {
        // process element here;
        // access column by index
        echo $i."---".$t[0].",".$t[1]." <br>";
        $i++;
    }

} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
    echo "error";
}


