<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>location session management</title>

<script src='/opt/kwynn/js/utils.js'></script>
<script src='js1.js'></script>

<style>
    body { font-family: sans-serif; }
	.dopt { margin-bottom: 1ex; }
</style>
</head>
<body>
<div id='selp'> <!-- selection parent -->
<?php 
require_once('location.php'); 
$locv = locSessCl::getVSS();
if ($locv) { ?>
	<p>To save a location cookie for <?php echo($locv); ?>, select expiration:</p>
	
	<form id='theForm10'>
	<div class='dopt'><input type='radio'  name='unit' value='now'/> <label>expire now / no cookie</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='session'/> <label>expire when browser closes</label></div>
	<div class='dopt'><input type='number' step='1' min='0' style='width: 4em; ' value='10' name='units' /></div>
	<div class='dopt'><input type='radio'  name='unit' value='1'   /> <label>seconds</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='60' checked='checked' /> <label>minutes</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='3600'   /> <label>hours</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='86400'  /> <label>days</label></div>
	<input type='hidden' name='cookieValue' value='<?php echo($locv); ?>' />
	<input type='hidden' name='cookieAction' value='setExpiration' />
	<input type='submit' value='save' /> 
	</form>
<?php }	else { ?>
	<p>no valid location sent</p>
<?php } ?>

</div> <!-- selection parent -->

<div id='resp'> <!-- result parent -->
	<pre id='rawrese' />
</div> <!-- result parent -->
	
<p>back to <a href='/t/22/02/geo/map.php'>map</a></p>
<p><a href='/'>home</a></p>
</body>
</html>
