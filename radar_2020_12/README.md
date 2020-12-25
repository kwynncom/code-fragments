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
