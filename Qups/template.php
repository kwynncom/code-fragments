<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>Q drop update tracker</title>

<style>
    body { 
		font-family: sans-serif; 
		font-size: 80%; 
	}
</style>
</head>
<body>
       
       <?php 
               require_once('btof.php'); 
               $GTHEO = new qanonBackToFrontClass();
       
       ?>
       
       <p>fetch time: <?php echo($GTHEO->getFetchTime()); ?>ms</p>
       
       <table>
               <thead>
                       <tr>
                               <th>etag</th>
						   <th>lm</th>
                               <th>asof</th>
                               <!-- <th>len</th> -->

                       </tr>
               </thead>
               <tbody>
                       <?php echo($GTHEO->getHTRows()); ?>
               </tbody>
       </table>
       
</body>
</html>
