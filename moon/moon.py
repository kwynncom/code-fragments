from skyfield.api import load
from skyfield import almanac

ts = load.timescale()
eph = load('/opt/de421.bsp')

t0 = ts.now() - 10
t1 = t0 + 100
t, y = almanac.find_discrete(t0, t1, almanac.moon_phases(eph))

print(t.utc_iso())
print(y)
print([almanac.MOON_PHASES[yi] for yi in y])
