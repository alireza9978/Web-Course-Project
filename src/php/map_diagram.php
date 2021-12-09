<html>
<body>

Welcome <br>
<p>
    Your chart type is: <?php echo $_POST["chart_type"]; ?>
</p>

<?php
$color = $_POST["color"];
echo '<p style="color:' . $color . ';">Your chart circle color is:' . $color . '</p>'; ?>

<p>
    Your chart caption is: "<?php echo $_POST["caption"]; ?>"
</p>

</body>
</html>