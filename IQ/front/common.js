const FMODE = 'none';
var START;
onDOMLoad(() => { START = time(); });

function feedback(a, isc) { 
	const t = time() - START;
	return kwjss.sobf('./../front/report/server.php', { _id : DBID, userAnswer: a, gotCorrect : isc, ms : t}); 
}
