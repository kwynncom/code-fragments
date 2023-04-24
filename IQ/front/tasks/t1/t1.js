var GVO;

onDOMLoad(() => {
	GVO = new t1inter();
	document.body.addEventListener('click', GVO.bc10);

});

class t1inter {
	
	bc20() { location.reload(); }
	
	bc10() {
		
		document.body.removeEventListener('click', GVO.bodyClick);
		
		byid('estatement').style.display = 'none';
		byid('equestion' ).style.display = 'block';
		
		qsa("[data-isqname='1']").forEach((e) => {
			e.style.visibility = 'visible';
		});
		
		qsa("[data-isqp='1'").forEach((e) => {
			e.style.visibility = 'visible';
			e.addEventListener('click', GVO.aclick);
		});
		
		byid('eclsc').style.visibility = 'hidden';
	}
	
	aclick() {
		const e = this;

		// byid('estatement').style.display = 'block';
		byid('esrepeat').style.visibility = 'visible';
		let color = 'red';
		if (e.dataset.iscor === '1') color = 'green';
		e.style.backgroundColor = color;
		
		byid('ebagain').style.visibility = 'visible';
		
		setTimeout(() => {		document.body.addEventListener('click', GVO.bc20);	}, 10);
	}
}
