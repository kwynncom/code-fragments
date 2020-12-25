<?php

// $s1 = 'https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd&TIME=2020-12-25T02%3A36%3A15.000Z&WIDTH=256&HEIGHT=256&SRS=EPSG%3A3857&BBOX=-9549125.069610499%2C3913575.8482010234%2C-9392582.035682458%2C4070118.8821290643';
// echo(urldecode($s1));
// https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd&TIME=2020-12-25T02:36:15.000Z&WIDTH=256&HEIGHT=256&SRS=EPSG:3857&BBOX=-9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643

// -9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643


// https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=application/openlayers3&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd&SRS=EPSG:3857&BBOX=-9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643

if (0) {
// removing time
$u1 = 'https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd&WIDTH=256&HEIGHT=256&SRS=EPSG:3857&BBOX=-9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643';
$res = file_get_contents($u1);
$len = strlen($res);
file_put_contents('/tmp/wxl1.png', $res);
// WORKS!
}

// removing tiles 
$u  = 'https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&LAYERS=conus_bref_qcd';

if (0) {
$u .= '&WIDTH=256';
$u .= '&HEIGHT=256';
$u .= '&SRS=EPSG:3857';
$u .= '&BBOX=';

$u .= '-9549125.069610499'; // MUST BE EXACT / STRING!!!
$u .= ',';
$u .= '3913575.8482010234';
$u .= ',';
$u .= '-9392582.035682458';
$u .= ',';
$u .= '4070118.8821290643';
}

if (1) { // WORKS!  I suspect I don't need an exact x y this time, just the proper ratios
$u .= '&WIDTH=512'; 
$u .= '&HEIGHT=512';
$u .= '&SRS=EPSG:3857';
$u .= '&BBOX=';

// The following seems close to the lat lon I want.  
$u .= '-9549125.069610499'; /* MUST BE EXACT / STRING!!! (or maybe not quite that precise once remove tiled) */ $u .= ',';
$u .= '3913575.8482010234'; $u .= ',';
$u .= '-9236039.001754420' ; $u .= ',';
$u .= '4226661.9160571000';
// 400: X,Y values for the tile index were calculated to be {33.499999995754365, 76.50000000174002} which had to be rounded to {33, 77} and exceeds the threshold of 10%. Perhaps the client is using the wrong origin ?
}

if (1) {
$res = file_get_contents($u);
$len = strlen($res);
file_put_contents('/tmp/wxl1.png', $res);
}