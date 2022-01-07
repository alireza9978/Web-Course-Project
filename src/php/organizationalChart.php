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



//$users = json_decode(file_get_contents('php://input'));

//if (jsonValidator($users)) {
//
//} else {
//    echo " \n\r  eeeeeerrrrrrorrrrrr";
//}
$json  = '{
    "Users": [
        {
            "postid": 0,
            "postTitle": "manager",
            "employeeName": "AmirMalekEsfandiari",
            "higherPostId": 0
        },
        {
            "postid": 1,
            "postTitle": "po",
            "employeeName": "ehsan",
            "higherPostId": 0
        },
        {
            "postid": 2,
            "postTitle": "po",
            "employeeName": "ali",
            "higherPostId": 0
        },
        {
            "postid": 3,
            "postTitle": "backend-developer",
            "employeeName": "ali",
            "higherPostId": 1
        },
        {
            "postid": 4,
            "postTitle": "po4",
            "employeeName": "abbas",
            "higherPostId": 2
        },
        {
            "postid": 5,
            "postTitle": "customer",
            "employeeName": "hasan",
            "higherPostId": 2
        },
        {
            "postid": 6,
            "postTitle": "cto",
            "employeeName": "hamed",
            "higherPostId": 3
        },
        {
            "postid": 7,
            "postTitle": "counter",
            "employeeName": "mohammad",
            "higherPostId": 4
        }
    ]
}';



$data=array(
    'manager'=>array(
//            'postId' => 0 ,

        'Vic persident Account Services'=>array(
            'Account Supervisor' => array(
                'a' => 'Account Executive',
                'aa'=>  'Account Executive'
            ),
            'aa' => 'Account Supervisor'
        ),
        'Vic President Creative Services'=>array(
            'a' => 'Art / Copy' ,
            'aa' => 'Production'
        ),
        'Vic President Marketing Services'=>array(
            'a' => 'Media',
            'aa' => 'Researcher'
        ),
        'Vic President Management Services'=>array(
            'a' =>'Accounting',
            'aaa' => 'customer',
            'aa' => 'Purchasing'
        ),
    )
);

echo '<div class="orgchart ">';
PHPtoOrgChart($data);
echo '</div>';
?>
</body>
</html>