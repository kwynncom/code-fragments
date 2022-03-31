var KWG_LOCSRVNM = '/t/22/02/geo/cookie/locCookSrv.php';

onDOMLoad(() => {
        const forme = byid('theLocCExpForm10');
        
        if (!forme) return;
        
	forme.addEventListener( "submit", function ( event ) {
	  event.preventDefault();
	  kwjss.sobf(KWG_LOCSRVNM, false, outLocCF, true, new FormData(forme));
	});
});

function sendExpireNow() {
    kwjss.sobf(KWG_LOCSRVNM, {'expireNow' : true});
}

function outLocCF(ra) {
    byid('rawrese').innerHTML = JSON.stringify(ra);
    return;
    
}

