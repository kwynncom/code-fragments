var GLWC = false;
class wallClock {
    
    constructor() {
        this.cs = [];
        this.myset();
    }
    
    q(de, te, we, cb) { 
       
        if (!de) this.wallProper();
        else    this.setExtra(de, te, we, cb);
        this.tick();
   
    } 
    
    setExtra(de, te, we, cb) {
        const t = {};
        t['date'] = de;
        t['time'] = te;
        t['dow' ] = we;
        t['cb'  ] = cb;
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
        const fs = ['dow', 'date', 'time', 'cb'];
        this.cs.forEach((cl) => {
            fs.forEach((f) => {
                if (cl[f]) if (f === 'cb') cl[f]();
                           else cl[f].value = h[f];
            });
        });
    }
     
}

onDOMLoad(() => { GLWC = new wallClock(); });
