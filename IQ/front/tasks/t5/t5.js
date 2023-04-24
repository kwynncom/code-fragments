class t5inter {
	static onclick(e, guess) {
		let color = 'red';
		let isc = false;
		if (guess == KWIQT5A) { color = 'green'; isc = true; }
		feedback(guess, isc);
		e.style.backgroundColor = color;
		setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
	}
}