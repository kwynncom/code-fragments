onDOMLoad(() => { new testDrag(); });

class kwDrag {
    
    constructor() {
        this.kwDragInit10();
        this.setDocumentLevelDrag();
    }
    
    kwDragInit10() {
        this.ableEs = [];
    }
    
    cmp(a, b) {
        const ai = this.getI(a);
        const bi = this.getI(b);
        if (ai > bi) return 'below';
        return 'above';
    }
    
    getI(e) {
        const row = this.getRow(e);
        const ch = this.thegpe.childNodes;
        const chn = ch.length;
        let di = -1;
        for (let i=0; i < chn; i++) {
            const che = ch[i];
            if (kwifs(che, 'dataset', 'kwDragIamP')) ++di;
            if (        this.getID(che) 
                    === this.getID(row)) return di;
        }
        
        kwas(false, 'getI() no result');
    }
    
    getID(e) {
        return kwifs(this.getRow(e), 'dataset', 'kwDragUqID');
    }
    
    setDocumentLevelDrag() {

        document.addEventListener("dragstart", (ev) => { 
            this.draggedE = ev.target;
        });

        document.addEventListener("dragenter", (ev) => { 
            const ovr = this.getRow(ev.target);
            if (!ovr) return;
            
            const oid = this.getID(ovr);
            const dreid = this.getID(this.draggedE);
            
            if (oid === dreid) return;
            
            if (this.cmp(this.draggedE, ev.target) === 'below') 
                 ovr.style.borderTop    = 'black dashed 4px';
            else ovr.style.borderBottom = 'black dashed 4px'; 
            
            // ovr.style.borderWidth = '4px';
            
            
        }); 
        document.addEventListener("drop"	 , (ev) => { 
            //self.ondrop(); 
            const droppedOnRow = this.getRow(ev.target);
            return;
        });
        
        document.addEventListener("dragover" , (ev) => { ev.preventDefault(); });        
    }
    
    
    
    
    setEleDraggable(e) { 
        e.draggable = true; 
        this.ableEs.push(e);
    }
    
    setDragParent(e, uq) {
        e.dataset.kwDragIamP = true;
        if (uq) e.dataset.kwDragUqID = uq;
            
         
    }
    
    getRow(e) {
      if (kwifs(e,'dataset','kwDragIamP')) return e;
      if (e.parentNode) return this.getRow(e.parentNode);
      return false;     
    }
    
    kwDragSetGrandParentE(e) {  this.thegpe = e;  }
    
}

class testDrag extends kwDrag {
    
    config() {
        this.charBase = 65;
        this.rowsn = 5;
        this.theParentE = byid('thetbody');
    }
    
    constructor() {
       super();
       this.config();
       this.kwDragSetGrandParentE(this.theParentE);
       this.init10(); 
    }
    
    init10() {
        
        for (let i = 0; i < this.rowsn; i++) {
            
            const idc = String.fromCharCode(this.charBase + i);
            const uqid = 'e_' + idc;
            
            const tr = cree('tr', uqid);
            this.setDragParent(tr, uqid);
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

