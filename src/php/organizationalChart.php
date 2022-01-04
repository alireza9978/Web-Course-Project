<?php

require '../../vendor/autoload.php';

use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;


function jsonValidator($data): bool
{
// Validate
    $validator = new JsonSchema\Validator;
    $validator->validate($data, (object)['$ref' => 'file://' . realpath('../json-scheme.json')]);

    if ($validator->isValid()) {
        echo "The supplied JSON validates against the schema.\n";
        return true;
    } else {
        echo "JSON does not validate. Violations:\n";
        foreach ($validator->getErrors() as $error) {
            printf("[%s] %s\n", $error['property'], $error['message']);
        }
        return false;
    }
}


$data = json_decode(file_get_contents('php://input'));

if (jsonValidator($data)) {
    var_dump($data);
} else {
    echo " \n\r  eeeeeerrrrrrorrrrrr";
}
