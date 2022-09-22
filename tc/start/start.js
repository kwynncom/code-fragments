class start {
    
    constructor() { 
        this.btne = byid('startBtn');
        this.es = {};
        this.onclick(); 
    }
    
    onclick() {
        if (this.btne.innerHTML !== 'start') return; 
        this.start();
    }
    
    start() {

        this.btne.innerHTML = 'stop';
        const p = this.p = byid('ssbase').cloneNode(true);
        this.curr = p;
        p.id = 'ssp_' + time();
        p.style.display = 'block';
        this.pop();
        byid('timep').append(p);
        GLOI.set();        
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
            if (!this.es[fs10[i]])    this.es[fs10[i]]       = {};
            if (!this.es[fs10[i]][fs20[j]]) this.es[fs10[i]][fs20[j]] = {};
            this.es[fs10[i]][fs20[j]] = e10;
        }
    
        this.elape = this.p.querySelector('[data-elap="1"]');
    
        GLWC.q(stes[0], stes[1], null, () => { this.ontick(); });
        
        
    }
    
    ontick() { 
        let d;
        // if (!(d = kwifs(this, 'es', 'start', 'date', 'value'))) return;
        
        const ums = Date.parse(this.es.start.date.value);
        return;
        
    }
}
