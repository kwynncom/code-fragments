class kwCalendar {
    constructor(parid, h1id, init) {
	this.pele = byid(parid);
	this.h1e  = byid(h1id);
	this.inito = init;
	this.dayi = 0;
	this.h1e.innerHTML = this.inito.monthh1;
	this.f10();
    }
    
    f10() {
	for (let j=0; j < 6; j++)
	for (let i=0; i < 7; i++) 
	 this.days(i,j);
	
    }
    
    days(d, w) {
	if (this.dayi >= this.inito.dinm) return;
	const de = cree('div');
	de.className = 'kwcald10';
	
	if (!(this.dayi === 0 && d < this.inito.day1w)) {
	    const dl = cree('div');
	    dl.innerHTML = ++this.dayi;
	    dl.className = 'kwcaldl10';
	    de.append(dl);
	}

	this.pele.append(de);
    }
}