var LAT;
var LON;

class kwGPS {
	constructor() {
		this.do10();
	}
	
	do10() {

		navigator.geolocation.getCurrentPosition(
			(p) => { 
				LAT = p.coords.latitude;
				LON = p.coords.longitude;
			},
			() => { /* err */}, 
			{ enableHighAccuracy: true }
		); 
		
		
/*     GPSOK(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude; */	
	}
}
