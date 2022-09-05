var net = require('net');

class sock {

    config() {
        this.ds = ['ipv4.kwynn.com', 'ipv6.kwynn.com'];
        this.port = 8123;
        this.minns = 1662349142572807324; // before 2022/09/05 00:21 EDT / New York
        this.stopat = 2;
        this.reci = 0;
        setTimeout(() => { process.exit(); }, this.stopat * 2 * 1000);

    }

    constructor() {
        this.config();
        this.dotcp();
    }

    dotcp() {

        const ds = this.ds
        for (let i=0; i < ds.length; i++) this.do1tcp(ds[i]);
    }

    do1tcp(domain) {
        const client = net.connect({port: this.port, host: domain}, () => {
            client.on('data', (res) => { this.parseRes(res); });
            client.write('a');
        });
    }

    parseRes(resraw) { // the raw string takes the form '1662349142572807324     \n' - a literal \n - does not trim and such

        const re = new RegExp(/\d+/);
        const s1 = resraw.toString();
        const a2 = s1.match(re);
        const s2 = a2[0];
        const theint = parseInt(s2);
        if (theint < this.minns) return;
        console.log(s2);
        if (++this.reci === this.stopat) process.exit();
    }
}

new sock();
