<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>MongoDB to HTML</title>

<script>
    <?php require_once('readPeople.php'); ?>
    
    var INIT_GVAR = <?php echo(json_encode($PEOPLE_GLOBAL)); ?>;
    window.onload = function() {
	INIT_GVAR.forEach(function() {}	);
    }
</script>
</head>
<body>
    <table>
	<thead>
	    <tr><th>name</th><th>age</th><th>species</th></tr>
	</thead>
	<tbody id='outTable10'>
	</tbody>
    </table>
    
</body>
</html>
