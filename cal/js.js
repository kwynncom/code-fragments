class kwCalendar {
    constructor(doin) {
	this.outer10();
	this.inito = kwCalMonthInfo(doin);
	this.dayi = 0;
	this.poph();
	this.f10();
    }
    
    poph() {
	this.h1e.append(this.getArrow(-1));
	this.h1e.append(this.inito.monthh1);
	this.h1e.append(this.getArrow(1));
    }
    
    getArrow(diri) {
	const e = cree('span');
	let arr = '>';
	if (diri < 0) arr = '<';
	e.innerHTML = arr; // depends on diri for next round
	const self = this;
	e.onclick = function() {
	    const d = new Date(self.inito.year, self.inito.moni + diri, 1);
	    new kwCalendar(d);
	}
	return e;
    }
    
    outer10() {
	const a = byid('kwCalAncestor');
	a.innerHTML = '';
	const h = cree('h1');
	h.id = 'monthh1';
	this.h1e = h;
	const p = cree('div');
	p.id = 'kwCalCalParent';
	a.append(h);
	a.append(p);
	this.pele = p;
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

function kwCalMonthInfo(doin) {
    const r = {};
    let dido; // display date object
    if (doin) dido = doin;
    else      dido = new Date();
    const moni = dido.getMonth();
    const year = dido.getFullYear();
    const d1 = new Date(dido.getFullYear(), moni, 1);
    
    r.day1w = d1.getDay();
    r.dinm  = new Date(year, moni + 1, 0).getDate();
    r.monthh1 = monthName(moni) + ', ' + dido.getFullYear();
    r.moni = moni;
    r.year   = year;
    return r;
}

function monthName(mjs0i) { // month JavaScript 0 index
    const monthNames = ["January", "February", "March", "April", "May", "June",	"July", "August", "September", "October", "November", "December" ];
    return monthNames[mjs0i];
}