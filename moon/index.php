<!DOCTYPE html>  <?php require_once('moon.php'); ?>
<html lang='en'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'    />
<meta name='viewport' content='width=device-width, initial-scale=1.0' />

<title>moon phase</title>

<!-- 
https://kwynn.com/t/22/01/moon.html
-->

<style>
body { font-family: sans-serif; }
td   { font-family: monospace; }
.mocl10 {
    width     : 2.8ex;
    text-align : center;
    display   : inline-block;
    padding: 0.5ex;
}

table {   border-collapse: separate; }

.cspan {
}
</style>

<script src='/opt/kwynn/js/utils.js'></script>

<script>



window.addEventListener('DOMContentLoaded', () => {   
    new moonCal();
}); 

class moonCal {
    constructor() { this.do10(); }
    
	getuni(ain) {
		if (ain.n === 0 && ain.pd === 1) return '&#127761;';
		if (ain.n === 0)				 return '&#127762;';
		if (ain.n === 1)				 return '&#127764;';
		if (ain.n === 2 && ain.pd === 1) return '&#127765;';
		if (ain.n === 2)				 return '&#127766;';
		if (ain.n === 3)				 return '&#127768;';
		// if (ain.
		
	}
	
    do10() {
		
		const monabig = <?php echo(moon::get()); ?>;
		const cala = monabig.cala;
		
        for (let i=0; i <= 45; i++) {
            const tr = cree('tr');
            this.do22(tr, cala[i]);
            byid('tbody10').append(tr);
      }
    }
    
    do22(tr, ain) {
		
		const tdd = cree('td');
		tdd.innerHTML = ain.hud;
		tr.append(tdd);
		
        const td = cree('td');
        td.style.backgroundColor = 'black';
        td.style.border = 'black';
        const span = cree('span');
        
        span.className = 'mocl10';
        
        const span20 = cree('span');
        span.append(span20);
        span20.innerHTML =  this.getuni(ain);;
        span20.className = 'cspan';
            
        span20.style.opacity = this.getOpacity(ain);
        td.append(span);
        tr.append(td);
		
		const tdl = cree('td');
		if (ain.t && ain.pd === 1) inht(tdl, ain.t + ' ' + ain.hut);
		tr.append(tdl);
    }
    
    getOpacity(ain) {
		let pd = ain.pd;
		
		const n = ain.n;
		
        if (n >= 2) pd = 9 - pd;
        if (n === 0 || pd === 1) return 0.35;

        if (n === 0 || n === 3)
        switch(pd) {
            case 1 : return 0.25;
            case 2 : return 0.35;
            case 3 : return 0.55;
            case 4 : return 0.58;
            case 5 : return 0.65;
            case 6 : return 0.78;
            case 7 : return 1;
            default : return 1;
        }
  
       if (n === 1 || n === 2)
        switch(pd) { 
            case 1 : return 0.70;
            case 2 : return 0.75;
            case 3 : return 0.83;
            case 4 : return 0.88;
            case 5 : return 0.93;
            case 6 : return 1;
            case 7 : return 1;
            case 8 : return 1;
         }
        
        return 1;
    }
   
}
</script>

</head>
<body>
    <p>
        <a href='/'>home</a>
        <a style='padding-left: 5ex;' href='/t/6/07/ql/quick_links.html'>ql</a>
        <a style='padding-left: 5ex;' href='https://www.timeanddate.com/moon/usa/atlanta'>T&D</a>
    </p>
    
    <p>Abandon ship!  Well, that was fun.  <a href='/t/7/11/blog.html#2022_0130_abandon_lunar_ship'>I explain my folly</a>.  
        
    </p>
    
    <p>The running number [when this was sort of working] is [was thought to be] the fraction of the moon's phase where 0 is new and 0.5 is full 
        and 0.99 is new again.</p>
    <p id='per10'></p>
    <table>
        <!-- <thead>
            <tr><th>at</th><th></th>
              <th>f</th><th>phase</th> 
            </tr>
        </thead> -->
        <tbody id='tbody10'>
        </tbody>
    </table>
    
    <!--
    https://stackoverflow.com/questions/11759992/calculating-jdayjulian-day-in-javascript
    https://en.wikipedia.org/wiki/Lunar_month
    // I copied very small parts by eye and hand, then confirmed stuff elsewhere
    https://jasonsturges.medium.com/moons-lunar-phase-in-javascript-a5219acbfe6e
    -->
</body>
</html>
