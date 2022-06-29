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

td.tdlen1 {
	font-size: 250%;
	font-weight: bold;
	
}
</style>
</head>
<body>
	<div>
		<div    style='display: inline-block; font-size: 150%; '><a href='https://qanon.pub/'>Q</a></div>
		<button style='display: inline-block; margin-left: 10ex; ' onclick='history.go(0);'>reload</button>
		<div    style='display: inline-block;'>more info at bottom</div>
	
	</div>
       <?php 
               require_once('btof.php'); 
               $GTHEO = new qanonBackToFrontClass();
       
       ?>
       
       <p><?php echo($GTHEO->getMeta()); ?></p>
       
       <table>
               <thead>
                       <tr>
                               <th>len</th>
						   <th>etag</th>
						   <th>lm</th>
                               <th>asof</th>


                       </tr>
               </thead>
               <tbody>
                       <?php echo($GTHEO->getHTRows()); ?>
               </tbody>
       </table>
       
	   <div>
		   <p><a href='https://github.com/kwynncom/code-fragments/tree/master/Qups'>source code for now</a></p>

		   <p><a href='https://github.com/kwynncom/code-fragments/tree/c31ec64639f658dcc41930485986489cd43654e5/Qups'>source code of a specific 
				   version</a> (probably old)</p>
   
		   <p><a href='/'>Kwynn's home</a>
			   
		   </p>
	   </div>
	   
</body>
</html>
