class t5inter {
	static onclick(e, guess) {
		let color = 'red';
		if (guess == KWIQT5A) color = 'green';
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}