onDOMLoad(() => { new testDrag(); });

class kwDrag {
    
    constructor() {
        this.kwDragInit10();
        this.setDocumentLevelDrag();
    }
    
    kwDragInit10() {
        this.ableEs = [];
    }
    
    setDocumentLevelDrag() {
        const self = this;

        document.addEventListener("dragstart", function(event) { 
            let row = this.getRow(event.target);
            self.draggedE = row;     
 /*           const i = row.dataset.i;
            self.draggedI = i; */
        });

        document.addEventListener("dragenter", function(ev) { 
        //    self.setDropI(ev.target);  
        });
        document.addEventListener("drop"	 , function(ev) { 
            //self.ondrop(); 
        });
        document.addEventListener("dragover" , function(ev) { ev.preventDefault(); 	     });        
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
      if (e.kwDragIamP) return e;
      if (e.parentNode) return this.getRow(e.parentNode);
      return false;     
    }
    
    
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
       this.init10(); 
    }
    
    init10() {
        
        for (let i = 0; i < this.rowsn; i++) {
            
            const idn = this.charBase + i;
            const uqid = 'e' + idn;
            
            const tr = cree('tr', uqid);
            this.setDragParent(tr);
            const td10 = cree('td');
            td10.innerHTML = '&varr;';
            this.setEleDraggable(td10);
            tr.append(td10);
            
            const td20 = cree('td');
            td20.innerHTML = String.fromCharCode(idn);
            tr.append(td20);
            
            this.theParentE.append(tr);
        }
    }
    
}

