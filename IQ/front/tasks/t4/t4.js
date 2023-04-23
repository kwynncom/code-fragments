class t4inter {
	static onclick(e) {
		let color = 'red';
		if (e.dataset.iamn === KWIQT4A) color = 'green';
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}