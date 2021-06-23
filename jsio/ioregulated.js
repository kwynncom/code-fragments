class kwior {
	
	config() {
		this.waitSend =  307; // prime number
		this.contSend = 2003; // same
	}
	
	static setAllEles() {
		document.querySelectorAll('input[type=text], textarea').forEach(function(e) {
			const o = new kwior(e);
		});
	}
	
	constructor(ele) { 
		this.ele = ele;
		this.config();
		this.init();
		this.setEle();

	}
	
	setEle() {
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
		this.ctov = setInterval(function () { self.send(); }, this.contSend);
	}

	clearInterval() {
		const temp = this.ctov; // does not fix race condition but might mitigate it
		this.ctov = false;
		clearInterval(temp);
	}

	onblur() { this.clearInterval(); }

	send() {
		const tosend = this.ele.value;
		console.log(this.ele.id + ' - checking send');
		if (tosend === this.okv) return;
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