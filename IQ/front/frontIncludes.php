<?php require_once('/opt/kwynn/kwutils.php');
$n = isrv('n'); ?>

<?php if ($n) { ?><title>IQ Task <?php echo($n); ?></title><?php } ?>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='common.js'></script>

<?php if ($n) {  ?><script src='tasks/t<?php echo($n); ?>/t<?php echo($n); ?>.js'></script> <?php } ?>
<script src='menuSide/menuSide.js'></script>

<link rel='stylesheet' href='common.css'>

<?php require_once(__DIR__ . '/commonFront.php'); ?>
