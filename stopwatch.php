<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    >
<meta name='viewport' content='width=device-width, initial-scale=1.0' >
<title>stopwatch</title>

<script src='/opt/kwynn/js/utils.js'></script>

<script> // stop and start working
class kwstopwatch {
    
    setU() {
	
	const e = window.parent.document.getElementById('kwstopwatch');
	const Us = e.dataset.u;
	let U = parseInt(Us);
	
	const min = 1327599879000;
	if (U < min) U *= 1000;
	kwas(U > min, 'timestamp too early');
	this.U = U;
    }
    
    constructor() {
	this.oute = byid('out10');
	this.setU();
	this.start();
    }
    
   
    
    start() {
	
	const interval = 269;
	this.doon10();
	this.theintv = setInterval(() => { this.doon10(); }, interval);
    }
    
    toggle() { 
	if (this.theintv) {
	    clearInterval(this.theintv);
	    this.theintv = null;
	    const e10 = byid('btn10');
	    e10.innerHTML = 'start';
	    const ignore46 = true;
	}
	else {
	    this.start();
	     byid('btn10').innerHTML = 'stop';
	}

    }
    
    doon10() {
	const dms = time() - this.U;
	const ds  = dms / 1000;
	const df  = ds.toFixed(1);
	inht(this.oute, df);
    }
    

}

var KWSW;

onDOMLoad(
    () => {
	KWSW = new kwstopwatch(); 
    }
);
</script>

</head>
<body style='padding: 0; margin: 0; '>
<div style='font-size: 10vw; '>
    <div id='out10' style='font-family: monospace; display: inline-block; '></div>
    <button id='btn10' style='display: inline-block; transform: scale(0.8, 0.8);  ' onclick='KWSW.toggle(); '>stop</button>
</div>

</body>
</html>
