window.onload = function() {
	if (0) kwior.setAllEles();
	else {
		const fs = ['e1', 'e2'];
		fs.forEach(function (eid) { 
			kwior.setEle(byid(eid), send, 'httpResponse'); }
			);
	}
}
