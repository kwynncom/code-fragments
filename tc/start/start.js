class start {
    
    constructor() { this.onclick(); }
    
    onclick() {
        const p = byid('ssbase').cloneNode(true);
        p.id = 'ssp_' + time();
        p.style.display = 'block';
        byid('timep').append(p);
    }
}
