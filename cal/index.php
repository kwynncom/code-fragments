<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>calendar</title>

<script src='./../utils.js'></script>
<script src='js.js'></script>
<script>
    <?php require_once('dates.php'); ?>
    window.onload = function() { new kwCalendar('kwCalCalParent', 'kwCalMonthH1', <?php echo(json_encode(kwCalInitDate())); ?>); }
</script>

<style>
    #kwCalCalParent  { 
        width: 100%;        
        height: 88vh; 
        display: flex;
        flex-flow: row wrap;
        align-content: flex-start;
        align-items: flex-start;
    }
    
    .kwcald10  { width: 13.9%; height: 17% ; border: solid black 2px; }
    
    h1 { text-align: center; margin-top:0; margin-bottom: 0.2em; font-size: 125%; }
    
    body { margin: 0.2em; font-family: sans-serif; }
    
    .kwcaldl10 { margin-top: 0.05em; margin-left: 0.15em; font-size: 110%; 
                         text-align: left; 
    }

</style>

</head>
<body>
    <div>
        <h1  id='kwCalMonthH1'></h1>
        <div id='kwCalCalParent' />
    </div>
</body>
</html>
