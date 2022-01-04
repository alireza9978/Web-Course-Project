<!DOCTYPE >
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link type="text/css" rel="stylesheet" href="../css/style.css"/>
</head>
<body>



<?php
//demo
require '../../vendor/autoload.php';
include './PHPtoOrgChart.php';
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



$users = json_decode(file_get_contents('php://input'));

//if (jsonValidator($users)) {
//
//} else {
//    echo " \n\r  eeeeeerrrrrrorrrrrr";
//}


$data=array(
    'a'=>array(
//            'postId' => 0 ,

        'aa'=>array(
            'aaa'=>'Mike',
            'aab'=>'Look',
            'aac'=>'Rum',
        ),
        'bb'=>array(
            'aaa'=>'123',
            'aab'=>'567',
            'aac'=>'890',
            'bbdd'=>array(
                'aaa'=>'123',
                'aab'=>'567',
                'aac'=>'890',
            ),
        ),
    )
);

echo '<div class="orgchart ">';
PHPtoOrgChart($data);
echo '</div>';
?>
</body>
</html>