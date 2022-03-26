var KWG_MAPU;

window.addEventListener('DOMContentLoaded', () => {
    
    const map = L.map('map').setView([33.58, -78.0], 4.5);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>'
    }).addTo(map);

    KWG_MAPU = new mapuse(map);
});

class mapuse {
    
    constructor(map) {
        this.map = map;
        this.init();
    }
    
    init() {
        this.map.on('click', (ev) => {this.setll('set', ev.latlng['lat'], ev.latlng['lng']); });
        this.map.on('mousemove', (ev) => { this.actll(ev.latlng['lat'], ev.latlng['lng']);  });
        byid('rmbtn').onclick = () => { this.setll('rm'); }
        byid('namei').oninput = (ele) => { 
            this.namef(ele.currentTarget); 
        }
    }
    
    namef(e) {
          const s = e.value;
        const re1 = /^[\w\-_]*$/;
        const r = re1.test(s);
        if (!r) {
            e.setCustomValidity('bad char');
            byid('saveb').disabled = true;
        }
        else {
            e.setCustomValidity('');
            byid('saveb').disabled = false;
        }
    }
    
    setll(ev, lat, lon) {
        if (this.marker) this.map.removeLayer(this.marker);
        if (ev === 'rm') {
            byid('rmp').style.display = 'none';
            qs('.instr10').style.display = 'block';
            this.map.on('mousemove', (ev) => { 
                this.actll(ev.latlng['lat'], ev.latlng['lng']); 
            });
            return;
        }
        
        this.actll(lat, lon);
        this.map.off('mousemove');
        this.marker = L.marker([lat, lon]).addTo(this.map);
        const cz = this.map.getZoom();
        let sz = 12;
        if (cz > 12) sz = cz + 1;
        this.map.setView(  [lat, lon], sz);
        byid('rmp').style.display = 'block';
        qs('.instr10').style.display = 'none';
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
