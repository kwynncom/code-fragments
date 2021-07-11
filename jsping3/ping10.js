class do1ping {
	constructor(cbf) {
		const no = new XMLHttpRequest();
		const self = this;
		no.onloadend = function() {
			const e = time();
			cbf(self.b, e, parseInt(this.responseText)); 
		}
		no.open('GET', 'server.php');
		this.b = time();
		no.send();
	}
}
