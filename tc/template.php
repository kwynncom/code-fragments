<!DOCTYPE html><html lang='en'><head><meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />
<title>timecard</title>
<?php 
    require_once('./utils.php'); 
    echo(jscssht::getAll(__DIR__));
?>
</head>
<body>
    <div>
        <?php require_once('./wall/wall.php');       ?>
        <?php require_once('onindicator/onind.php'); ?>
        <?php require_once('./start/start.php');     ?>
    </div>
    <div class='bbord' style='margin-top: 1ex; margin-bottom: 0.5ex;'>&nbsp;</div>
    <?php require_once('./start/startStop.php'); ?>
</body>
</html>
