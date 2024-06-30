class t2inter {
	static async onclick(e, ans, isc) {
		
		await feedback(ans, isc);
		let color = 'red';
		if (isc) color = 'green';
		if (FMODE === 'feedImm') {
			e.style.backgroundColor = color;
			setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
		} else location.reload();
	}
	
	

}