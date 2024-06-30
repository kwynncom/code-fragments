var GLST;

class start {
    
    constructor() { 
        this.btne = byid('startBtn');
        this.es = {};
    }
    
    onclick() {
        const b = this.btne;
        if (b.innerHTML !== 'start') {
            b.style.visibility = 'hidden';
            this.btne.innerHTML = 'start';
            GLWC.deq(this.qn);
            GLOI.unset(); 
            b.style.visibility = 'visible';
            return; 
        }
        this.start();
    }
    
    start() {

        this.btne.innerHTML = 'stop';
        const p = this.p = byid('ssbase').cloneNode(true);
        this.curr = p;
        p.id = 'ssp_' + time();
        p.style.display = 'block';
        this.pop();
        byid('timep').prepend(p);
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
    
        this.qn = GLWC.q(stes[0], stes[1], null, () => { this.ontick(); });
        
        
    }
    
    ontick() { 
        const sta = Date.parse( this.es.start.date.value  + ' ' +
                                this.es.start.time.value);
        const sto =  Date.parse(this.es.stop. date.value + ' ' +
                                this.es.stop. time.value);
        
        const d10 = sto - sta;
        const d20 = d10 / 60000;
        this.elape.innerHTML = d20.toFixed(2);
        return;
    }
}

onDOMLoad(() => {GLST = new start(); });