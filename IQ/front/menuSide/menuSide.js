var FMODE = 'none';

class feedMode {
	constructor(vin) {
		kwjss.sobf('/t/23/04/IQ/front/menuSide/feedServ.php', { setTo : vin }, this.onret);
	}
	
	onret(ret) {
		if (!(ret && ret.v)) return; 
		FMODE = ret.v;
		byid(FMODE).checked = true;
	}
}

new feedMode();
