class kwior_21_1 {
	
	config() {
		this.waitSend  =  307; // prime number
		this.contSend  = 2003; // same
		this.timeoutS  =    7;
		this.timeoutms =  this.timeoutS * 1000;
	}
	
	static setAllEles() { document.querySelectorAll('input[type=text], textarea').forEach(function(e) { new kwior(e);	}); }
	
	static setEle(e, cb)  { new kwior_21_1(e, cb);	}
	
	constructor(ele, cb) { 
		this.ele = ele;
		this.sendCB = cb;
		this.config();
		this.init();
		this.setEleOb();
	}
	
	setEleOb() {
		kwas(this.ele.id, 'kwior - ele must have id');
		const self = this;
		this.ele.oninput = function() { self.oninput(); }
	}
	
	oninput() {
		const self = this;
		this.lastits = time();
		if (this.wtov) clearTimeout(this.wtov);
		this.wtov = setTimeout (function () { self.send(); }, this.waitSend);
		if  (this.ctov) return;
		this.ctov = setInterval(function () { self.send(); self.checkInterval(); }, this.contSend);
	}

	isTO() {
		if (time() - this.lastits < this.timeoutms) return false;
		
		this.clearInterval();
		return true;
	}

	clearInterval() {
		if (this.ctov) clearInterval(this.ctov);
		this.ctov = false;
	}
	
	checkInterval() {
		if (this.isokv()) this.clearInterval();
	}
	
	isokv() { return this.ele.value === this.okv; }

	send() {
		console.log(this.ele.id + ' - checking send');
		if (this.isokv()) return;
		if (this.isTO()) return;
		console.log(this.ele.id + ' - SEND');
		this.sendCB(this.ele);
	}
	
	onOKSend(vv) {
		this.okv = vv;		
	}
	
	init() { 
		this.wtov = false;
		this.ctov = false;
		this.sendingv = false;
		this.okv = false;
	}
}