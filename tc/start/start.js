class start {
    
    constructor() { this.onclick(); }
    
    onclick() {
        const p = this.p = byid('ssbase').cloneNode(true);
        p.id = 'ssp_' + time();
        p.style.display = 'block';
        this.pop();
        byid('timep').append(p);
    }
    
    pop() {
        const t = getHu();
        const fs10 = ['start', 'stop'];
        const fs20 = ['date' , 'time'];
        const stes = [];
        
        for (let i=0; i < fs10.length; i++) 
        for (let j=0; j < fs20.length; j++) {
            const e10 = this.p.querySelector('[data-stasto="' + fs10[i]  + '"][type="' + fs20[j] + '"]');
            e10.value = t[fs20[j]];
            setRDAttrs(e10);
            if (i === 1) stes[j] = e10;
        }
    
        new wallClock(stes[0], stes[1]);
        
        
    }
}
