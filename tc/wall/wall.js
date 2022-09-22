var GLWC = false;
class wallClock {
    
    constructor() {
        this.cs = [];
        this.myset();
    }
    
    q(de, te, we) { 
       
        if (!de) this.wallProper();
        else    this.setExtra(de, te, we);
        this.tick();

        
    } 
    
    setExtra(de, te, we) {
        const t = {};
        t['date'] = de;
        t['time'] = te;
        t['dow' ] = we;
        this.addTickEs(t);
    }
    
    addTickEs(ain) {
        this.cs.push(ain);
    }
    
    wallProper() { 
        const fs = ['dow', 'date', 'time'];
        const t = [];
        fs.forEach((f) => { t[f] = byid('wall' + f); });
        this.addTickEs(t);
    }
    
    myset() {
        this.tick();
        if (this.siv) return;
        this.siv = setInterval(() => { this.tick(); }, 1000);      
    }
    

    tick() {
        const h = getHu();
        const fs = ['dow', 'date', 'time'];
        this.cs.forEach((cl) => {
            fs.forEach((f) => {
                if (cl[f]) 
                    cl[f].value = h[f];
            });
        });
    }
     
}

onDOMLoad(() => { GLWC = new wallClock(); });
