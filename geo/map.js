class mapuse {
    
    initMap(lla, zin) {
        const map = L.map('map').setView(lla, zin);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 19,
        }).addTo(map);        
        this.map = map;        
    }
    
    constructor(lla, zin, isdefault) {
        this.initMap(lla, zin);
        this.initControls();
        this.initGPS();
        if (!isdefault) this.setll('set', lla[0], lla[1]);
    }
    
    initGPS() {
        for(let i=1; i <=2; i++)
        byid('btncltogeo' + i).onclick = () => { 
            navigator.geolocation.getCurrentPosition((pos) => { this.GPSOK(pos);}, 
                                                     (   ) => { this.GPSerr()  ;}, { enableHighAccuracy: true }); 
        };
    }
    
    GPSOK(position) {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;

        this.setll('set', lat, lon);        
    }
    
    GPSerr() {
        inht('latlone', 'error');   
    }
    
    initControls() {
        this.map.on('click', (ev) => {this.setll('set', ev.latlng['lat'], ev.latlng['lng']); });
        this.map.on('mousemove', (ev) => { this.actll(ev.latlng['lat'], ev.latlng['lng']);  });
        byid('rmbtn').onclick = () => { this.setll('rm'); }
    }
    
    setll(ev, lat, lon) {
        if (this.marker) this.map.removeLayer(this.marker);
        if (ev === 'rm') {
            byid('rmp').style.display = 'none';
            qs('.instrp10').style.display = 'block';
            this.map.on('mousemove', (ev) => { 
                this.actll(ev.latlng['lat'], ev.latlng['lng']); 
            });
            return;
        }
        
        this.actll(lat, lon);
        this.map.off('mousemove');
        this.marker = L.marker([lat, lon]).addTo(this.map);
        this.map.setView(  [lat, lon], this.map.getZoom() + 2);
        byid('rmp').style.display = 'block';
        qs('.instrp10').style.display = 'none';
        byid('saveb').disabled = byid('rmbtn').disabled = false;
       
    }
    
    actll(lat, lon) {

        const a = [lat, lon];
        let s = '';
        for (let i=0; i < 2; i++) {
            s += a[i].toFixed(6);
            if (i === 0) s += ' ';
        }

        byid('latlone').innerHTML = s;
    }
}
