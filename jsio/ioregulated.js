class kwior {
	
	config() {
		this.waitSend =  300;
		this.contSend = 2000;
	}
	
	constructor(eid) { 
		kwas(eid, 'kwior must be given id');
		this.eid = eid;
		this.config();
		this.init();
	}
	
	oninput() {
		
		this.incnt++;
		this.ints = time();
		
		// const sendimm = time() - this.sendts > this.contSend;
		
		const self = this;

		if (this.tov) clearTimeout(this.tov);
		this.tov = setTimeout(function () { self.send(); }, this.waitSend);
	}
	
	send() {
		this.sendts = time();
		console.log(this.eid + ' - send');
	}
	
	init() { 
		this.sendts = 0;
		this.ints   = 0;
		this.incnt  = 0;
	}
}