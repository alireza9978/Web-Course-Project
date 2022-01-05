<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="test.css">

</head>
<body>
<?php

$main_image_width = 500;
$main_image_height = 500;
$final_image = imagecreatetruecolor($main_image_width, $main_image_height);

$output_path = "out_text.png";
// Create a colour.
$white = imagecolorallocate($final_image, 255, 255, 255);
$grey = imagecolorallocate($final_image, 128, 128, 128);
$black = imagecolorallocate($final_image, 0, 0, 0);

imagefill($final_image, 0, 0, $black);
$font = 'C:\Users\Alireza\PhpstormProjects\Web-Course-Project\test\arial.ttf';
$chart_caption = "testing text";

imagettftext($final_image, 50, 0, $main_image_width / 2, $main_image_height / 2, $white, $font, $chart_caption);


// output the picture
imagepng($final_image, $output_path);
// Destroy the image handler.
imagedestroy($final_image);
?>
<img src="<?php echo $output_path ?>" alt="iran map">
</body>
</html>