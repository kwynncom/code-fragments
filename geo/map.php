<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>map / Leaflet test</title>


<script src='/opt/kwynn/js/utils.js'></script>

<link rel="stylesheet" href="/opt/leaflet/leaflet.css" 
      integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />

<script src="/opt/leaflet/leaflet.js" 
																								<!-- wA== -->
		integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
   
<script src='cookie/js1.js'></script>
<script src='map.js'></script>


<script>

<?php require_once('config.php');
	  $a = getMapSettings();
	  extract($a); unset($a);?>
		  
window.addEventListener('DOMContentLoaded', () => {

	const lla = <?php echo('[' . $ia[0] . ',' . $ia[1] . ']'); ?>;
	const mz  = <?php echo($zoom); ?>;
   
   new mapuse(lla, mz, <?php echo($isdefaultLoc ? 'true' : 'false'); ?>);
});

function locCookieF(lleid, formeid, formid) {
	const ll = byid(lleid).innerHTML;
	byid(formeid).value = ll;
	byid(formid).submit();
}

</script>

<link rel="stylesheet" href="map.css" /> <style></style>

</head>
<body>
    <div id='mappar'>
        <div id='lligp'>
            <div class='instrp10'>
                <div class='homep'><a href='/'>hm</a></div>
                <div class='instr10'>click / touch map to set point or <button class='orGPS' id='btncltogeo2'>GPS</button></div>
            </div>
            <div id='rmp' style='display: none'>
                <form id='locForm10' style='display: none' method='post' action='cookie/locTemplate.php'>
                    <input type='hidden' name='latlonssForm' id='latlonssForm' />
                </form>
                <div class='homep'><a href='/'>hm</a></div>
                <button id='saveb' disabled='disabled' onclick='locCookieF("latlone", "latlonssForm", "locForm10");'>save / manage</button>
                <button id='rmbtn' disabled='disabled'>remove</button>
                <button style='margin-left: 3ex; ' id='btncltogeo1'>GPS</button>
            </div>
            <div id='llipar'>
                <span id='latlone'></span>
            </div>
        </div>
        <div id='map'></div>
    </div>
</body>
</html>

