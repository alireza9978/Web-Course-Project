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
$circle_diameter = 100;
$main_image_width = 2560;
$main_image_height = 2560;

$image_path = "map.png";
$output_path = "out.png";
$im = imagecreatefrompng($image_path);
// Create a colour.
$white = imagecolorallocate($im, 255, 0, 0);
// Draw a cirlce in the middle of the image.
imagefilledellipse($im, $main_image_height / 2, $main_image_height / 2, $circle_diameter, $circle_diameter, $white);
// Save the image to a file.
// output the picture
imagepng($im, $output_path);
// Destroy the image handler.
imagedestroy($im);
?>
<img src="<?php echo $output_path ?>" alt="iran map">
</body>
</html>