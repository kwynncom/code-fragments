window.onload = function() {
	document.querySelectorAll('input[type=text], textarea').forEach(function(e) {
		const o = new kwior(e.id); 
		e.oninput = function() { o.oninput(); }
	});
}
