class t3inter {
	static async onclick(e) {
		let color = 'red';
		let isc = false;
		const ans = e.dataset.iamn;
		if (ans === KWIQT3A) { color = 'green'; isc = true; }
		await feedback(ans, isc);
		if (FMODE === 'feedImm') {
			e.style.backgroundColor = color;
			setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
		} else location.reload();
		
	}
}