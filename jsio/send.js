function send(ein, cb) {
    
    const burl = 'server.php?XDEBUG_SESSION_START=netbeans-xdebug';
    const XHR = new XMLHttpRequest(); 
    XHR.open('POST', burl);
    XHR.onloadend = function() {
		cb(this.responseJSON, this.responseText);
    }
    
	const sob = {};
	sob.eid		= ein.id;
	sob.dataset = ein.dataset;
	sob.v       = ein.value;
		
    const formData = new FormData();
    formData.append('POSTob',JSON.stringify(sob)); 
    XHR.send(formData);
}
