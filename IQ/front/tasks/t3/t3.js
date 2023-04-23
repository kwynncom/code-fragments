class t3inter {
	static onclick(e) {
		let color = 'red';
		if (e.dataset.iamn === KWIQT3A) color = 'green';
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}