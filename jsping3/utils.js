function byid(id) { return document.getElementById(id); }
function kwas(v, msg) {
	if (!v) {
		if (!msg) msg = 'unknown message';
		throw msg;
	}
}
function time()      { return (new Date().getTime()); } 
function cree(etype) { return document.createElement(etype);}
function roundTo(val, digits) {
    if (!digits) digits = 0;

    const pow = Math.pow(10, digits);
    const mul = val * pow;
    const rnd = Math.round(mul);
    const ret = rnd / pow;

    return ret;
}
