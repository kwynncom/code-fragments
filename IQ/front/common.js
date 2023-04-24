const FMODE = 'none';
var START;
onDOMLoad(() => { START = time(); });

function feedback(a, isc) { 
	const t = time() - START;
	return kwjss.sobf('./../back/wserver.php', { _id : DBID, a: a, isc : isc, ms : t}); 
}
