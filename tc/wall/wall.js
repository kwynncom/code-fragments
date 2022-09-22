class wallClock {
    constructor(de, te, we) { 
        if (!de) {
            this.fs = ['dow', 'date', 'time'];
            this.wallProper();
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
            this[f].value = h[f];
        });
        
    }
     
}
