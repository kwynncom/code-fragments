<?php
require_once('head.html');
?>
<body> 
<?php require_once('do10.php');

echo(odsDo10Cl::getHT());

?>
<div style='margin-top: 1.1em; '><button onclick='window.location.reload();'>redo</button>
    <div>as of <?php echo(date('r')); ?></div>
</div>
<?php require_once('info_frag.html'); ?>
</body>
</html>
