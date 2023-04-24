class t3inter {
	static onclick(e) {
		let color = 'red';
		let isc = false;
		const ans = e.dataset.iamn;
		if (ans === KWIQT3A) { color = 'green'; isc = true; }
		feedback(ans, isc);
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}