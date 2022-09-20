class wallClock {
    constructor() { this.do10(); } 
    do10() { 
        const fs = ['dow', 'date', 'time'];
        fs.forEach((f) => { this[f] = byid('wall' + f); });
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
