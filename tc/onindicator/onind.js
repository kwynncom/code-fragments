var GLOI;

class onIndicator {
    constructor() {
        this.e = byid('indicatore');
        this.i = 0;

    }
    
    set(is) {
        this.tick();
        this.sih = setInterval(() => this.tick(), 200);
    }
    
    unset() { 
        clearInterval(this.sih); 
        this.e.style.color = 'red';
        this.e.style.opacity = 1;
    }
    
    tick() {
        if (this.i++ % 4 <= 1) {
            this.e.style.color = tickColor();
            this.e.style.opacity = 1;
        }
        else {
            this.e.style.opacity = 0;
        }
    }
}


onDOMLoad(() => { GLOI = new onIndicator(); });

function tickColor() { return '#66FF00'; }
