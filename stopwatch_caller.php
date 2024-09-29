<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    >
<meta name='viewport' content='width=device-width, initial-scale=1.0' >
<title>call stopwatch</title>



</head>
<body>

<?php
    require_once('/opt/kwynn/kwutils.php');
    $U = time();
?>
<iframe id='kwstopwatch' src='./stopwatch.php' style='width: 10em; height: 1.6em; border: none; font-size: 90%; ' 
    data-u='<?php echo($U); ?>'>

</iframe>
<div>
<?php 

    echo(date('r', $U)); 
?>
</div>

</body>
</html>
