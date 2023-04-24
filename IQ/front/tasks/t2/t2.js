class t2inter {
	static onclick(e, ans, isc) {
		
		feedback(ans, isc);
		let color = 'red';
		if (isc) color = 'green';
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
	
	

}