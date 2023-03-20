class solarTZAdj {

	static getCurrentDSTOffset() {
		const jan = new Date(new Date().getFullYear(), 0, 1);
		return ((new Date().getTimezoneOffset()) - jan.getTimezoneOffset()) * 60 * 1000;
	}

	static getDateO() {

		const o = solarTZAdj.getCurrentDSTOffset();
		const Ums = time() + o;
		return new Date(Ums);
	} // https://stackoverflow.com/questions/11887934/how-to-check-if-dst-daylight-saving-time-is-in-effect-and-if-so-the-offset/11888430#11888430 
}	  // edited Mar 12, 2018 at 3:54, Sheldon Griffin

function validOrDefaultLoc(lat, lon) { // make this a class
	
	const alat = Math.abs(lat);
	const alon = Math.abs(lon);
	
	if (is_numeric(lat) && is_numeric(lon)) {
		// if (alat 
		
	}
	
	
	const o = {};
	o.lat =  34.236667;
	o.lon = -84.160556;
	o.des = 'top of Sawnee Mountain, Cumming, Georgia, USA';
	
	return o;
}

function getSunPos(lat, lon) {
	
	if (!lat) lat = 0;
	if (!lon) lon = 0;
	
const mdo = solarTZAdj.getDateO();

const tDate = mdo;
const ttime = tDate.getTime();

const st = solar_table(new Date(ttime), 34, -84);
const jd = jd_from_epoch(ttime);
const jc = jcent_from_jd(jd);
const elevr = solar_elevation_from_time(jc, 34, -84);
const elevd = DEG(elevr);
const decl = solar_declination(jc);
const ra   = sun_apparent_lon(jc);
// const radectoaltaz = require('radectoaltaz');
const coordinates = new RaDecToAltAz(DEG(ra),DEG(decl),34,-84, tDate);
const alt = coordinates.getAlt();
const az = coordinates.getAz();
console.log(alt, az);
const ignore = true;

}