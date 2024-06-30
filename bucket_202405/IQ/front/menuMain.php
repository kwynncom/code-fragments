<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>IQ test</title>

<style>
    body { font-family: sans-serif; }
	li   { margin-bottom: 1em; }

</style>

<?php require_once('frontIncludes.php'); ?>

<script>
	onDOMLoad(() => { byid('rightMenu').style.visibility = 'visible';});
</script>

</head>
<body>
	<div style='width: 10em; position: relative; '>
	<?php require_once(__DIR__ . '/commonFront.php'); menuSide(); ?>
	</div>
</body>
</html>
