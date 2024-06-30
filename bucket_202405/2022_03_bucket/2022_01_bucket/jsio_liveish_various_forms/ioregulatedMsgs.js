class kwior_messages {
	constructor(mid, vele) {
		this.mase = byid(mid);
		this.vele = vele;
		this.init();
	}
	
	init() { this.gen('No changes made.');	}
	sending() { this.gen('saving...', 'yellow');}
	err(o) { 
		if (o && o.msg) this.gen(o.msg, 'red');
	}
	
	change() { this.gen('waiting...', 'yellow'); }
	
	ok(v) {
		if (v !== this.vele.value) return;
		this.gen('OK', 'rgb(153, 255, 153)');
	}
	
	gen(t, c) {
		this.mase.innerHTML = t;
		if (!c) return;
		this.vele.style.backgroundColor = c;
	}
	
}