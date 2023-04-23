onDOMLoad(() => {
	document.body.addEventListener('click', t1inter.bodyClick);
});

class t1inter {
	static bodyClick() {
		
		document.body.removeEventListener('click', t1inter.bodyClick);
		
		byid('estatement').style.display = 'none';
		byid('equestion' ).style.display = 'block';
		
		qsa("[data-isqname='1']").forEach((e) => {
			e.style.visibility = 'visible';
		});
		
		qsa("[data-isqp='1'").forEach((e) => {
			e.style.visibility = 'visible';
			e.addEventListener('click', t1inter.aclick);
		});
		
		
	}
	
	static aclick() {
		const e = this;

		byid('estatement').style.display = 'block';
		let color = 'red';
		if (e.dataset.iscor === '1') color = 'green';
		e.style.backgroundColor = color;
	}
}
