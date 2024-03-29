class validLatLon {
	
	static get(lat, lon) {
		const o = new validLatLon(lat, lon);
		return o.getI();
	}
	
	constructor (lat, lon) {
		this.des = '';
		this.valid = false;
		
		try {
			this.validOrEx(lat, lon);

			this.lat = lat;
			this.lon = lon;
			this.valid = true;
			return;
		} catch(ex) { }
		
		this.setDefault();
	}
	
	validOrEx(lat, lon) {
		kwas(is_numeric(lat) && is_numeric(lon), 'non-numeric lat and / or lon');
		
		const alat = Math.abs(lat);
		const alon = Math.abs(lon);
		
		kwas(alat <=  90.0001, 'invalid latitude');
		kwas(alon <= 180.0001, 'invalid longitude');
		
		return true;
	}
	
	setDefault() {
		this.lat =  34.236667;
		this.lon = -84.160556;
		this.des = 'top of Sawnee Mountain, Cumming, Georgia, USA';
		this.valid = true;
	}
}

function getSunPos() {
	
	const llo = new validLatLon(LAT, LON);
	if (!llo.valid) return;
	
	const lat = llo.lat;
	const lon = llo.lon;
	
	
			// const mdo = solarTZAdj.getDateO();

	const mdo = new Date();


	const tDate = mdo;
	const ttime = tDate.getTime();

	const st = solar_table(new Date(ttime), 34, -84); // sunset, sunrise, noon, twilights, etc.; for later

	const jd = jd_from_epoch(ttime);
	const jc = jcent_from_jd(jd);
	const elevr = solar_elevation_from_time(jc, lat, lon);
	const elevd = DEG(elevr);
	const decl = solar_declination(jc);
	const ra   = sun_apparent_lon(jc);
	const stl  = sun_true_lon(jc); // just curious
	const rad = DEG(ra);

	const coordinates = new RaDecToAltAz(rad,DEG(decl), lat, lon, tDate);
	const alt = coordinates.getAlt();
	const az = coordinates.getAz();
	const ignore = true;
	
	return { 'lat' : lat, 'lon' : lon, 'des' : llo.des, 'azu' : az, 'alt' : alt, 'tab' : st };

}
