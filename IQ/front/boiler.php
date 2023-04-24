<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<?php require_once('/opt/kwynn/kwutils.php');
$n = isrv('n'); ?>
<title>IQ Task <?php echo($n); ?></title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='common.js'></script>
<script src='tasks/t<?php echo($n); ?>/t<?php echo($n); ?>.js'></script>

<link rel='stylesheet' href='common.css'>

<?php require_once(__DIR__ . '/commonFront.php'); ?>