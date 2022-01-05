<!DOCTYPE html>
<span class="badge badge-pill badge-warning" style="padding: 8px;font-size: 1.1vw; margin-top: -5%;">map diagram</span>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Cloud</title>
    <link rel="stylesheet" href="../css/word-cloud-style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

</head>
<body>
    <?php

        function shuffle_assoc($list) {
            if (!is_array($list)) return $list;
        
            $keys = array_keys($list);
            shuffle($keys);
            $random = array();
            foreach ($keys as $key)
            $random[$key] = $list[$key];
        
            return $random;
        }

        function rand_color() {
            return '#' . str_pad(dechex(mt_rand(0, 0x999999)), 6, '0', STR_PAD_LEFT);
        }

        $word_cloud_array_counted = array(
            "Hadi" => 13,
            "Alireza" => 14,
            "Maryam" => 9,
            "Parnian" => 8  ,
            "temp" => 5,
            "test" => 9,
            "فردوسی" => 14,
            "دانشگاه" => 15,
            "وب" => 15,
            "web" => 14,
            "pooya" => 12,
            "cloud" => 20,
            "word" => 18,
            "project" => 20,
            "iran" => 9,
            "output" => 17,
            "php" => 25,
            "json" => 14,
            "html" => 10,
            "برنامه نویسی" => 10,
            "group" => 8,
            "diagram" => 11,
            "term" => 10
        );

        $sum_of_count = 0;
        foreach ($word_cloud_array_counted as $key=>$value){
            $sum_of_count += $value;
        }
    ?>
    <div class="container">
        <?php 
            $sum_val = 0;
            foreach (shuffle_assoc($word_cloud_array_counted) as $key => $val) {
                $sum_val += $val;
                // for ($i=0; $i>=($sum_val / ($sum_of_count * 4)); $i++){
                // print_r("<div style='whit-space: pre'> </div>");
                // }
                $size = 0;
                if ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.9) ) {
                    $size = 4;
                }
                elseif ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.8) ) {
                    $size = 3.5;
                }
                elseif ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.7) ) {
                    $size = 3;
                }
                elseif ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.6) ) {
                    $size = 2.5;
                }
                elseif ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.5) ) {
                    $size = 2;
                }
                elseif ( $val > ($sum_of_count / sizeof($word_cloud_array_counted) * 0.4) ) {
                    $size = 1.5;
                }
                else {
                    $size = 1;
                }
                print_r("<div class='word ".(rand(1, 3)==1 ? 'rotate' : '')."' id='$key' style='font-size:".$size."em;color:".rand_color().";'>$key</div>");
                if ($sum_val > ($sum_of_count / 4)){
                    $sum_val = 0;
                }
            };

    ?>
    </div>
</body>
</html>