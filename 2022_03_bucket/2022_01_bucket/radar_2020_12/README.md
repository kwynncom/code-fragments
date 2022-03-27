I'm starting from https://radar.weather.gov/#/

This is the new radar format as of mid-December, 2020.  I believe you can still get at individual radars, but I might as well parse this new one.

Then I zoom in, centered on Atlanta, with southern TN and Warner Robbins, GA as my N - S bounds.  

Based on the queries I see in the Dev Tools, I created the map queries in w1.php

I need the p1.py transform to go from lat lon to the "SRS=EPSG:3857" format that the map uses.  I *might* be able to use lat lon directly by 
re-specifying the SRS as mentioned in "GetCapabilities", but I suspect that would cause other problems due to pixel ratios.

With the 3857 format, both the north south and east west difference (x and y) are, in my relevant zoom scale, exactly 156543.033928041

This is a 611.496226281408 ratio from x y coordinates to pixels; otherwise put, 156543.033928041 / 256 pixels = 611.49...

If one uses &TILED=true, you need to know where the Openlayers tiles connect to the 3857 format, or something like that.  I can re-connect with tiles on 
my own, later.  By turning tiles off, I suspect I have a lot more flexibility in making radar calls.

My current version of w1.php is pretty close to the area I want to fetch for myself.  What I need to figure out next is if one can make more-or-less 
arbitrary calls as long as one's pixel requests are consistent.  Or does one even need pixels (height width) without tiles?  ANSWER: yes.

So, the next test will be to see if can request something other than multiples of 256 or 128 or whatever.



***********
***********
https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetCapabilities

results in XML

https://opengeo.ncep.noaa.gov/geoserver/conus/conus_bref_qcd/ows?SERVICE=WMS&VERSION=1.1.1&REQUEST=GetCapabilities&TILED=true


********
This WORKS:

https://epsg.io/transform#s_srs=4326&t_srs=3857&x=-86.0000000&y=32.0000000

86 W becomes x -9573476.21
32 N becomes y  3763310.63
82 W becomes x -9128198.25
35 N becomes y  4163881.14

*************
/opt/conda/bin/conda install -c conda-forge pyproj
# cython3 - C-Extensions for Python 3
# sudo apt install cython3
# pip uninstall pyproj
# sudo pip2 uninstall pyproj
# https://docs.conda.io/en/latest/miniconda.html#linux-installers

https://github.com/conda-forge/proj.4-feedstock


**********

https://ferret.pmel.noaa.gov/LAS/documentation/end-user-documentation/google-earth-products/basic-wms-requests

keywords 1: NOAA OWS WMS GetMap image png bbox

You have a compound coordinate reference system (CRS). EPSG::32636 represents the horizontal coordinate reference system, 
WGS 84 / UTM zone 36 North. This is a projected, rather than a geodetic coordinate system, but it's based on WGS 84, 
a geodetic coordinate reference system. EPSG::5773 is a vertical coordinate reference system, EGM96 height.


EPSG:3857
WGS 84 / Pseudo-Mercator -- Spherical Mercator, Google Maps, OpenStreetMap, Bing, ArcGIS, ESRI

Transform Get position on a map
This is projected coordinate system used for rendering maps in Google Maps, OpenStreetMap, etc. For details see Tile system.

***************8
***********
