class kwCalendar {
    constructor(parid, h1id, init) {
	this.pele = byid(parid);
	this.h1e  = byid(h1id);
	this.inito = kwCalMonthInfo();
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

function kwCalMonthInfo() {
    const r = {};
    const now = new Date();
    const d1 = new Date(now.getFullYear(), now.getMonth(), 1);
    
    r.day1w = d1.getDay();
    r.dinm  = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();
    r.monthh1 = monthName(now.getMonth()) + ', ' + now.getFullYear();
    return r;
}

function monthName(mjs0i) { // month JavaScript 0 index
    const monthNames = ["January", "February", "March", "April", "May", "June",
      "July", "August", "September", "October", "November", "December"
    ];
    return monthNames[mjs0i];
}