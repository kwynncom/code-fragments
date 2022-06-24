onDOMLoad(() => { new testDrag(); });

class dragKwVisClass {
    onOver(ede, dir, ove) {
        
        if (this.doe) this.doe.style.borderTop = this.doe.style.borderBottom = 'none';
        
        if (dir === 'clear') return;
        
        if (dir === 'above') 
             ove.style.borderTop    = 'black dashed 4px';
        else ove.style.borderBottom = 'black dashed 4px';
        
        this.doe = ove;
    }
    
    
}

class dragKwOrdClass {
    
    constructor() {
        this.maxx = 0;
    }
    
    
    inite(e, ordx, interval) {
        this.interval = interval;
        ordx = parseInt(ordx);
        kwas(ordx > 0, 'order x must be > 0');
        e.dataset.kwOrdx = ordx;
        if (ordx > this.maxx) this.maxx = ordx;
    }
    
    set (ae, be, ce) {
        const es = [ae, be, ce];
        const xs = [];
        
        for (let i=0; i < es.length; i++) {
            xs[i] = this.getx(es[i], i);
        }
        
        const ordx = (xs[0] + xs[2]) / 2;
        kwas(ordx > 0, 'ordx <= 0 set kwdrag');
        be.dataset.kwOrdx = ordx;
    }
    
    getx(e, i) {
        if (!e && i === 0) return 0;
        if (!e) return this.maxx + this.interval;
        const ordx = parseInt(e.dataset.kwOrdx);
        kwas(ordx > 0, 'ordx <= 0 getx ordx dragkw');
        return ordx;
    }

}

class dragKwClass {
    
    constructor() {
        this.dragKwInit10();
        this.setDocumentLevelDrag();
    }
    
    dragKwInit10() {
        this.ableEs = [];
        this.viso = new dragKwVisClass();
        this.ordo = new dragKwOrdClass();
    }
    
    cmp(a, b) {
        const ai = this.getI(a);
        const bi = this.getI(b);
        if (ai < bi) return 'below';
        return 'above';
    }
    
    calcNewOrd(drr) {
        const dri = this.getI(drr);
        const prev = this.getRowByI(dri - 1);
        const next = this.getRowByI(dri + 1);
        this.ordo.set(prev, drr, next);
        
    }
    
    getRowByI(iin) {
        if (iin < 0) return false;
        const ch = this.thegpe.childNodes;
        const chn = ch.length;        
        if (iin >= chn) return false;
        
        let di = -1;
        for (let i=0; i < chn; i++) {
            const che = ch[i];
            if (kwifs(che, 'dataset', 'dragKwIamP')) ++di;
            if (di === iin) return che;
        }
        
        return false;
    }
    
    getI(e) {
        const row = this.getRow(e);
        const ch = this.thegpe.childNodes;
        const chn = ch.length;
        let di = -1;
        for (let i=0; i < chn; i++) {
            const che = ch[i];
            if (kwifs(che, 'dataset', 'dragKwIamP')) ++di;
            if (        this.getID(che) 
                    === this.getID(row)) return di;
        }
        
        kwas(false, 'getI() no result');
    }
    
    getID(e) {
        return kwifs(this.getRow(e), 'dataset', 'dragKwUqID');
    }
    
    setDocumentLevelDrag() {

        document.addEventListener("dragstart", (ev) => { 
            this.draggedE = ev.target;
        });

        document.addEventListener("dragenter", (ev) => { 
            const ovr = this.getRow(ev.target);
            if (!ovr) return; // definitely can happen if dragged outside
            this.viso.onOver(this.draggedE, this.cmp(this.draggedE, ev.target), ovr);
            this.dorow = ovr;
           
        }); 
        document.addEventListener("drop"	 , (ev) => { 
            const dir = this.cmp(this.draggedE, this.dorow);
            const edr = this.getRow(this.draggedE);
            if (dir === 'above') 
                 this.insertBefore(this.dorow, edr);
            else this.insertAfter (this.dorow, edr);
            
            this.calcNewOrd(edr);
            
            this.viso.onOver(false, 'clear');
            
        });
        
        document.addEventListener("dragover" , (ev) => { ev.preventDefault(); });        
    }
    

    insertBefore(stayingEle, movingEle) { stayingEle.parentNode.insertBefore(movingEle, stayingEle); /* insertBefore is a JS function */ }
    insertAfter (stayingEle, movingEle) { var se2 = stayingEle.nextSibling; if (se2) this.insertBefore(se2, movingEle); else movingEle.parentNode.append(movingEle);}    
    
    
    setEleDraggable(e) { 
        e.draggable = true; 
        this.ableEs.push(e);
    }
    
    setDragParent(e, uq, ordx) {
        e.dataset.dragKwIamP = true;
        if (uq) e.dataset.dragKwUqID = uq;
        this.ordo.inite(e, ordx);
            
         
    }
    
    getRow(e) {
      if (kwifs(e,'dataset','dragKwIamP')) return e;
      if (e.parentNode) return this.getRow(e.parentNode);
      return false;     
    }
    
    dragKwSetGrandParentE(e) {  this.thegpe = e;  }
    
}

class testDrag extends dragKwClass {
    
    config() {
        this.charBase = 65;
        this.rowsn = 5;
        this.theParentE = byid('thetbody');
    }
    
    constructor() {
       super();
       this.config();
       this.dragKwSetGrandParentE(this.theParentE);
       this.init10(); 
    }
    
    init10() {
        
        for (let i = 0; i < this.rowsn; i++) {
            
            const idc = String.fromCharCode(this.charBase + i);
            const uqid = 'e_' + idc;
            
            const tr = cree('tr', uqid);
            this.setDragParent(tr, uqid, (i + 1) * 100, 100);
            const td10 = cree('td');
            td10.innerHTML = '&varr;';
            this.setEleDraggable(td10);
            tr.append(td10);
            
            const td20 = cree('td');
            td20.innerHTML = idc;
            tr.append(td20);
            
            this.theParentE.append(tr);
        }
    }
    
}

