onDOMLoad(() => {
	byid('theForm10').addEventListener( "submit", function ( event ) {
	  event.preventDefault();
	  kwjss.sobf('locCookSrv.php', false, outLocCF, true, new FormData(byid('theForm10')));
	});
});

function outLocCF(ra) {
    byid('rawrese').innerHTML = JSON.stringify(ra);
    return;
    
}