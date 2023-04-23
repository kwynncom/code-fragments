class t1inter {
	static bodyClick() {
		byid('estatement').style.display = 'none';
		byid('equestion' ).style.display = 'block';
		
		qsa("[data-qname='1']").forEach((e) => {
			e.style.visibility = 'visible';
		});
	}
}

