class do1ping {
	constructor(cbf) {
		const no = new XMLHttpRequest();
		const self = this;
		no.onloadend = function() {
			const e = time();
			const st = parseInt(this.responseText);
			const d = st - self.b + e - st;
			cbf(d); 
		}
		no.open('GET', 'server.php');
		this.no = no;
	}
	
	ping() {
		this.b = time();
		this.no.send();
	}
}
