class kwCalendar {
    constructor(parid) {
	this.f10(parid);
    }
    
    f10(parid) {
	const pele = byid(parid);
	let tot = 0;
	for (let i=0; i < 7; i++)
	for (let j=0; j < 5; j++) this.days(i,j, ++tot, pele);
    }
    
    days(d, w, tot, pele) {
	if (tot > 31) return;
	const de = cree('div');
	de.className = 'kwcald10';
	const dl = cree('div');
	dl.innerHTML = tot;
	dl.className = 'kwcaldl10';
	de.append(dl);
	pele.append(de);
    }
}