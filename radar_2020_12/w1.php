<?php

$s1 = 'https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&FORMAT=image%2Fpng&TRANSPARENT=true&TILED=true&LAYERS=conus_bref_qcd&TIME=2020-12-25T02%3A36%3A15.000Z&WIDTH=256&HEIGHT=256&SRS=EPSG%3A3857&BBOX=-9549125.069610499%2C3913575.8482010234%2C-9392582.035682458%2C4070118.8821290643';
echo(urldecode($s1));
