from pyproj import Proj, transform

P3857 = Proj(init='epsg:3857')
P4326 = Proj(init='epsg:4326')

lat = 33
lon = -84

x,y = transform(P4326, P3857, lon, lat)

print(x,y)
