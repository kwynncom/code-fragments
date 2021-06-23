class kwior {
	
	config() {
		this.waitSend =  307; // prime number
		this.contSend = 2003; // same
	}
	
	static setAllEles() { document.querySelectorAll('input[type=text], textarea').forEach(function(e) { new kwior(e);	}); }
	
	static setEle(id)  { new kwior(byid(id));	}
	
	constructor(ele) { 
		this.ele = ele;
		this.config();
		this.init();
		this.setEleOb();

	}
	
	setEleOb() {
		kwas(this.ele.id, 'kwior - ele must have id');
		const self = this;
		this.ele.oninput = function() { self.oninput(); }
		this.ele.onblur  = function() { self.onblur (); }
	}
	
	oninput() {
		const self = this;
		if (this.wtov) clearTimeout(this.wtov);
		this.wtov = setTimeout (function () { self.send(); }, this.waitSend);
		if  (this.ctov) return;
		this.ctov = setInterval(function () { self.send(); self.checkInterval(); }, this.contSend);
	}

	clearInterval() {
		if (this.ctov) clearInterval(this.ctov);
		this.ctov = false;
	}

	onblur() { /* this.clearInterval(); */ }
	
	checkInterval() {
		if (this.isokv()) this.clearInterval();
	}
	
	isokv() { return this.ele.value === this.okv; }

	send() {
		console.log(this.ele.id + ' - checking send');
		if (this.isokv()) return;
		console.log(this.ele.id + ' - SEND');
		this.okv = this.ele.value;
		
	}
	
	init() { 
		this.wtov = false;
		this.ctov = false;
		this.sendingv = false;
		this.okv = false;
	}
}