class t5inter {
	static onclick(e) {
		let color = 'red';
		if (e.dataset.iamn == KWIQT5A) color = 'green';
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}