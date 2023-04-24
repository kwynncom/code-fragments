const FMODE = 'none';

async function feedback(a, isc) {
	const pr = kwjss.sobf('./../back/wserver.php', { _id : DBID, a: a, isc : isc});
	await pr;
	return pr;
	
}