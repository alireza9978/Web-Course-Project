<?php

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$reader = new Xls();

$spreadsheet = $reader->load("../raw/states.xls");

try {
    $target_data = [];
    $sheetData = $spreadsheet->getSheet(0)->toArray();
    foreach ($sheetData as $t) {
        $target_data[$t[0]] = $t[1];
    }
    print_r($target_data);
} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
    echo "error";
}


