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

class moon {
    
    setPhase(p) {
        let ph = '';
        let dir;
        let phpr;
            
        const fulld = 1 / moon.getMoonth();
        
        if   (p <= 0.5) dir = 'waxing';
        else            dir = 'waning';
        
        this.direction = dir;
        
        ph += dir + ' ';
        
        if      (Math.abs(p - 0.5) <= fulld) {
            phpr = 'full';
            this.muni = '&#127765;';
            
        }
        else if (Math.abs(p - 1.0) <= fulld) {
            phpr = 'new';
            this.muni = '&#127761;';
        }
        else if (p <= 0.25 || p >= 0.75 ) {
            if (dir === 'waning') this.muni = '&#127768;'
            else                  this.muni = '&#127762;'
            phpr = 'crescent';
        }
        else {
            if (dir === 'waxing') this.muni = '&#127764;';
            else                  this.muni = '&#127766;';
            phpr = 'gibbous';
        }
        
        this.phaseProper = phpr;
        
        ph += phpr;
         
        this.phaseWhole = ph;
    }
}

window.addEventListener('DOMContentLoaded', () => {   
    // new displayMoon();
    new moonCal();
}); 

class moonCal {
    constructor() { this.do10(); }
    
	getuni(ain) {
		if (ain.pd === 1 && ain.n === 0) return '&#127761;';
		
	}
	
    do10() {
		
		const monabig = <?php echo(moon::get()); ?>;
		const cala = monabig.cala;
		
        for (let i=0; i <= 45; i++) {
            const tr = cree('tr');
            this.do22(tr, cala[i]);
            // this.do30(tr, mo);
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

        const c = this.getuni(ain);
        
//        const ca = this.color(mo);
        
        span.style.backgroundColor = 'black';
        
        span.className = 'mocl10';
        
        const span20 = cree('span');
        span.append(span20);
        span20.innerHTML = c;
        span20.className = 'cspan';
            
        // span20.style.opacity = ca;
        td.append(span);
        tr.append(td);
    }
    
    color(mo) {
        
        const pp = mo.phaseProper;
        let pd = parseInt(Math.ceil(mo.phaseDay));
        if (mo.direction === 'waning') pd = 9 - pd;

        if (mo.phaseProper === 'new') return 0.35;

        if (pp === 'crescent')
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
    
        if (pp === 'gibbous')
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
