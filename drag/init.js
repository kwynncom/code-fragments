onDOMLoad(() => { new testDrag(); });

class testDrag {
    
    config() {
        this.rowsn = 5;
        this.theParentE = byid('thetbody');
    }
    
    constructor() {
       this.config();
       this.init10(); 
    }
    
    init10() {
        for (let i = 0; i < this.rowsn; i++) {
            const tr = cree('tr');
            const td10 = cree('td');
            td10.innerHTML = '&varr;';
            tr.append(td10);
            
            const td20 = cree('td');
            td20.innerHTML = String.fromCharCode(65 + i);
            tr.append(td20);
            
            this.theParentE.append(tr);
        }
    }
    
}

