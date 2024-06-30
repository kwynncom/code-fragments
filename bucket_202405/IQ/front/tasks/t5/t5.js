class t5inter {
	static async onclick(e, guess) {
		let color = 'red';
		let isc = false;
		if (guess == KWIQT5A) { color = 'green'; isc = true; }
		await feedback(guess, isc);
		if (FMODE === 'feedImm') {
			e.style.backgroundColor = color;
			setTimeout(() => { document.body.addEventListener('click', () => { location.reload(); }); }, 10);
		} else location.reload();
	}
}