<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>location session management</title>

<script src='/opt/kwynn/js/utils.js'></script>

<script>

onDOMLoad(() => {
	byid('theForm10').addEventListener( "submit", function ( event ) {
	  event.preventDefault();
	  kwjss.sobf('sesssrv.php', false, false, false, new FormData(byid('theForm10')));
	});
});

</script>

<style>
    body { font-family: sans-serif; }
	.dopt { margin-bottom: 1ex; }
</style>
</head>
<body>
<?php 
require_once('location.php'); 
$ljson = locSessCl::json_encode();
if ($vs) { ?>
	<p>To save a location cookie, select expiration:</p>
	
	<form id='theForm10'>
	<div class='dopt'><input type='radio'  name='unit' value='session'/> <label>expire when browser closes</label></div>
	<div class='dopt'><input type='number' step='1' min='0' style='width: 4em; ' value='10' nume='units' /></div>
	<div class='dopt'><input type='radio'  name='unit' value='60' checked='checked' /> <label>minutes</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='3600'   /> <label>hours</label></div>
	<div class='dopt'><input type='radio'  name='unit' value='86400'  /> <label>days</label></div>
	<input type='hidden' name='cookieName' value='location' />
	<input type='hidden' name='loclatlonss' value='<?php echo($vs); ?>' />
	<input type='submit' value='save' /> 
	</form>
<?php }	else { ?>
	<p>no valid location sent</p>
<?php } ?>

<p>back to <a href='/t/22/02/geo/map.html'>map</a></p>
<p><a href='/'>home</a></p>
</body>
</html>
