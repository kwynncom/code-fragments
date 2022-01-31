from skyfield.api import load
from skyfield import almanac
import json

ts = load.timescale()
eph = load('/opt/de421.bsp')

t0 = ts.now() - 1.5
t1 = t0 + 45
t, y = almanac.find_discrete(t0, t1, almanac.moon_phases(eph))

print(t.utc_iso())
# print(y)
# print([almanac.MOON_PHASES[yi] for yi in y])
# print(t.utc_iso(), y, [almanac.MOON_PHASES[yi] for yi in y])
# json.dumps(t.utc_iso(), y, [almanac.MOON_PHASES[yi] for yi in y])

res = []
# res.append(t.utc_iso().tolist())
a1 = t.utc_iso()
# l1 = a1.tolist()
# res.append(y)
# res.append([almanac.MOON_PHASES[yi] for yi in y])
# json.dumps(res)
# res20 = res.tolist()
# json.dumps(a1)
