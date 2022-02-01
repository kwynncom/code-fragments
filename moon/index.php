<!DOCTYPE html>
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
    
    constructor(at) {
        const f     = this.fractionFromNew0V = moon.fractionFromNew0(at);
        this.setPhase(f);
        this.fillum = moon.calcIllum(f);
        this.quart  = moon.getMoonth() / 4;
        this.moonth = moon.getMoonth();
        this.setPhaseCalcs(f);
        return;
    }
    
    setPhaseCalcs(f) {
        const dayOfMoon = this.dayOfMoon = f * this.moonth;
        const quarterF   = dayOfMoon / this.quart;
        const quarterI   = Math.floor(quarterF);
        this.quarter     = parseInt(quarterI) + 1;
        this.phaseDay   = dayOfMoon - quarterI * this.quart;
        this.phasePercent = this.phaseDay / this.quart;
        return;
    }
    
    static calcIllum(f) {
        const i = (0.5 - Math.abs(f  - 0.5)) * 2;
        return i;
    }

    static fractionFromNew0(nowmsin) {
        let nowms;
        if (nowmsin) nowms = nowmsin;
        else         nowms = time();
        const julianUNIXEpoch = 2440587.5;
        const msinday = 86400000;
        const epdays = (nowms / msinday);
        const jd = epdays + julianUNIXEpoch;
        const moonth = moon.getMoonth();
        const newMoon_2000_0106_Gregorian = 2451550.1;
        const edays = jd - newMoon_2000_0106_Gregorian;
        const moonths = edays / moonth;
        const floor = Math.floor(moonths);
        const  perToNew = moonths - floor;
        return perToNew;
    }
    
    static getMoonth() { return 29.5305888531; }
    
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
/*
window.addEventListener('DOMContentLoaded', () => {   
    new displayMoon();
    new moonCal();
}); */

class displayMoon {
    
    config() {
        this.e10 = byid('per10');
        this.dinterval = 150;
        this.decright = 8;
    }
    
    constructor() {
        this.config();
        this.onInt();
        this.setInt();
    }

    onInt() {
        const p = moon.fractionFromNew0(time());
        const pd = p.toFixed(this.decright);
        this.e10.innerHTML = pd;
    }

    setInt() {
        const self = this;
        // setInterval(() => { self.onInt(); }, this.dinterval);       
    }
}

class moonCal {
    constructor() { this.do10(); }
    
    baseADJ(no, i) {
        const eodo = new Date(no.getFullYear(), no.getMonth(), no.getDate() + i, 23, 59, 59, 999);
        // const tzoms = eodo.getTimezoneOffset() * 60000;
        return eodo;
    }
    
    do10() {
        const no   = new Date();        
        for (let i=0; i <= 120; i++) {
            const tr = cree('tr');
            const dob = this.baseADJ(no, i);
            const mo = new moon(dob.getTime());
            if (i === 0) {
                this.pdir = mo.direction;
                this.pppr  = this.phaseProper;
            }
            this.do20(tr, dob);
            this.do22(tr, mo);
            this.do30(tr, mo);
            byid('tbody10').append(tr);
      }
    }
    
    do22(tr, mo) {
        const ill  = mo.fillum;
        const byte = parseInt(Math.round(mo.fillum * 255));
        let s = '';
        s += 'rgb(' + byte + ',' + byte + ',' + byte + ')';
        const td = cree('td');
        td.style.backgroundColor = 'black';
        td.style.border = 'black';
        const span = cree('span');

        const c = mo.muni;
        const tdbg = ((75 * ill * 1.5));
        
        const ca = this.color(mo);
        
        span.style.backgroundColor = 'black';
        
        span.className = 'mocl10';
        
        const span20 = cree('span');
        span.append(span20);
        span20.innerHTML = c;
        span20.className = 'cspan';
            
        span20.style.opacity = ca;
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
    
    gray(p) { 
       const c = parseInt(Math.round(p / 100 * 255)); 
       return 'rgb(' + c + ',' + c + ',' + c + ')';        
    }
    
    do25(tr, mo) {
       const f = mo.fillum;
       const d = parseInt(Math.round(f * 100));
       const td = cree('td');
       td.innerHTML = d;
       tr.append(td);
       
    }
    
    do30(tr, mo) {
        
        const nowdir = mo.direction;
        const nowppr = mo.phaseProper;
        let v = '';
        if   (this.pdir !== nowdir) {
            if (mo.direction === 'waxing') v = 'new';
            else                           v = 'full';
        }
        
        if (     (nowppr === 'crescent' && this.pppr === 'gibbous' )
             ||  (nowppr === 'gibbous'  && this.pppr === 'crescent') ) {
         
                if (mo.direction === 'waxing') v = 'gibbous';
                else                           v = 'crescent';
        }
        
        this.pdir = nowdir;
        this.pppr = nowppr;
        
        const td20 = cree('td');
        td20.innerHTML = v;
        
        const td30 = cree('td');
        inht(td30, mo.fractionFromNew0V);
        tr.append(td20);
        tr.append(td30);
     }

    do20(tr, dob) {
        const td10 = cree('td');
        td10.innerHTML = dob.toString(); // .substring(0,10);
        tr.append(td10);
    }
    
    colors() {
        
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
