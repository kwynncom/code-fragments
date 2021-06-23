class kwior {
	constructor(eid) { 
		kwas(eid, 'kwior must be given id');
		this.eid = eid;
	}
	
	oninput() {
		console.log(this.eid);
	}
	
}