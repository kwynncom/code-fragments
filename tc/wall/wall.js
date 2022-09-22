class wallClock {
    constructor(de, te, we) { 
        if (!de) {
            this.fs = ['dow', 'date', 'time'];
            this.wallProper();
        } else {
            this['date'] = de;
            this['time'] = te;
        }
        
        this.myset();
        
    } 
    
    wallProper() { 
        const fs = this.fs;
        fs.forEach((f) => { this[f] = byid('wall' + f); });
    }
    
    myset() {
        this.tick();
        setInterval(() => { this.tick(); }, 1000);        
    }
    

    tick() {
        const h = getHu();
        const fs = ['dow', 'date', 'time'];
        fs.forEach((f) => {
            if (this[f]) 
                this[f].value = h[f];
        });
        
    }
     
}
