<!DOCTYPE html><html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />
<title>timecard</title>
<?php 
    require_once('./utils.php'); 
    echoAllJSCSS();
?>
</head>
<body>
    <div>
        <?php require_once('./wall/wall.php'); ?>
        <div    class='ibccl' id='indicatore' style='color: red;'>&#11044;</div>
        <?php require_once('./start/start.php'); ?>
    </div>
    <div class='bbord' style='margin-top: 1ex; margin-bottom: 0.5ex;'>&nbsp;</div>
    <?php require_once('startStop.php'); ?>
</body>
</html>
