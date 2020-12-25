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

// BBOX=-9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643
$u  = 'https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image/png&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd';

if (0) {
$u .= '&WIDTH=256';
$u .= '&HEIGHT=256';
$u .= '&SRS=EPSG:3857';
$u .= '&BBOX=';

// // -9549125.069610499,3913575.8482010234,-9392582.035682458,4070118.8821290643


// MUST BE EXACT / STRING!!!
$u .= '-9549125.069610499';
$u .= ',';
$u .= '3913575.8482010234';
$u .= ',';
$u .= '-9392582.035682458';
$u .= ',';
$u .= '4070118.8821290643';

echo $u;
if (1) {
$res = file_get_contents($u);
$len = strlen($res);
file_put_contents('/tmp/wxl1.png', $res);
}

}