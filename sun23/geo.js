var LAT;
var LON;
var DES = '';

class kwGPS {
	constructor() {
		this.do10();
	}
	
	do10() {

		navigator.geolocation.getCurrentPosition(
			(p) => { 
				LAT = p.coords.latitude;
				LON = p.coords.longitude;
				DES = 'GPS';
			},
			() => { 
				/* err */
				const ignore = true;
			}, 
			{ enableHighAccuracy: true }
		); 
		
		
/*     GPSOK(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude; */	
	}
}
