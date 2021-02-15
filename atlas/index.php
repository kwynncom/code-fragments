<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>MongoDB to HTML</title>

<script>
    
    function byid(id) { return document.getElementById(id); }
    function cree(ty) { return document.createElement (ty); }
    
    
    <?php require_once('readPeople.php'); ?>
    
    var INIT_GVAR = <?php echo(json_encode($PEOPLE_GLOBAL)); ?>;
    window.onload = function() {
	INIT_GVAR.forEach(function(row) {
	    const tr = cree('tr');
	    for(const [key, value] of Object.entries(row)) {
		if (key !== 'age' && key !== 'species' && key !== 'name') continue;
		const td = cree('td');
		td.innerHTML = value;
		tr.append(td);
	    }
	    byid('outTable10').append(tr);
	});
    }
</script>
</head>
<body>
    <table>
	<thead>
	    <tr><th>name</th><th>species</th><th>age</th></tr>
	</thead>
	<tbody id='outTable10'>
	</tbody>
    </table>
    
</body>
</html>
