<!DOCTYPE html>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    >
<meta name='viewport' content='width=device-width, initial-scale=1.0' >
<title>stopwatch</title>

<script src='/opt/kwynn/js/utils.js'></script>

<script> // 4:52am - iframe starting to work
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
	this.doit();
    }
    
    doit() {
	
	const interval = 269;
	this.doon10();
	this.theintv = setInterval(() => { this.doon10(); }, interval);
    }
    
    doon10() {
	const dms = time() - this.U;
	const ds  = dms / 1000;
	const df  = ds.toFixed(2);
	// const df = df10.r
	inht(this.oute, df);
    }
    

}

onDOMLoad(
    () => {
	new kwstopwatch();
    }
);
</script>

</head>
<body>
<div id='out10'>

</div>


</body>
</html>
