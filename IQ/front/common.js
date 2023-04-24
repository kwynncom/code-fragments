var START;
onDOMLoad(() => { START = time(); });

function feedback(a, isc) { 
	const t = time() - START;
	return kwjss.sobf('./../front/report/server.php', { _id : DBID, userAnswer: a, gotCorrect : isc, ms : t}); 
}

function getAnswerClickE() {
		let e = kwifs(byid('answerMain'));
		if (!e) e = document.body;
		return e;
}
