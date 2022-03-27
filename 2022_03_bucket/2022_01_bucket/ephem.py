from skyfield.api import load
# https://rhodesmill.org/skyfield/

# Create a timescale and ask the current time.
ts = load.timescale()
t = ts.now()

# Load the JPL ephemeris DE421 (covers 1900-2050).
# planets = load('de421.bsp')
# the file is 17MB
planets = load('/opt/de421.bsp')
earth, mars = planets['earth'], planets['mars']

# What's the position of Mars, viewed from Earth?
astrometric = earth.at(t).observe(mars)
ra, dec, distance = astrometric.radec()

print('Mars from earth')
print(ra)
print(dec)
print(distance)

from skyfield.framelib import ecliptic_frame
eph = planets
sun, moon = eph['sun'], eph['moon']
e = earth.at(t)

_, slon, _ = e.observe(sun).apparent().frame_latlon(ecliptic_frame)
_, mlon, _ = e.observe(moon).apparent().frame_latlon(ecliptic_frame)
phase = (mlon.degrees - slon.degrees) % 360.0

print('moon rel to 360 d: {0:.1f}'.format(phase))

