class do1ping {
	constructor(cbf) {
		const no = new XMLHttpRequest();
		const self = this;
		no.onloadend = function () {
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

class draw1Ping {
	constructor(tbid, diob, groupn) {
		this.tb = byid(tbid);
		this.diob = diob;
		this.groupn = groupn;
		this.do10();
	}

	do10() {
		const tr = cree('tr');
		this.tb.prepend(tr);
		const tdst = cree('td');
		tdst.className = 'eldi';
		tdst.innerHTML = '&nbsp;';
		const pos = [];
		let i = 0;
		const self = this;
		for (i = 0; i < this.groupn; i++) {
			const td = cree('td');
			tr.append(td);
			pos[i] = new do1ping(function (d) {
				self.diob.reg(d, tdst, td);
				self.iter1();
			}
			);
		}
		tr.append(tdst);
		this.pos = pos;
		this.iterInit();
	}

	iterInit() {
		this.iteri = 0;
		this.pos[this.iteri].ping();
	}

	iter1() {
		if (this.pos[++this.iteri])
			this.pos[this.iteri].ping();
	}
}
	