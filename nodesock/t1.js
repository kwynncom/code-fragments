var net = require('net'); // TCP
var udp = require('dgram');

class sock { // executed below

    config() {
        this.ds = {'4' : 'ipv4.kwynn.com', '6' : 'ipv6.kwynn.com'};
        this.port = 8123;
        this.minns = 1662349142572807324; // before 2022/09/05 00:21 EDT / New York - this is in the past
        this.stopat = 4;
        this.reci = 0;
        this.theres = [];
        setTimeout(() => { process.exit(); }, 5000);

    }

    constructor() {
        this.config();
        this.dotcp();
        this.doudp();
    }

    doudp() {

        for (const [ipv, dom] of Object.entries(this.ds)) {
            var server = udp.createSocket('udp' + ipv);
            server.on('message', (msg) => { this.procRes(msg, dom, 'udp', ipv);   });
            server.send('a', this.port, dom);
        }
    }

    dotcp() {
        for (const [ipv, dom] of Object.entries(this.ds)) this.do1tcp(dom, ipv);
    }

    do1tcp(domain, ipv) {
        const client = net.connect({port: this.port, host: domain}, () => {
            client.on('data', (res) => { this.procRes(res, domain, 'tcp', ipv); });
            client.write('a');
        });
    }

    procRes(resraw, domain, ptype, ipv) { // see note at bottom about this processing
        const re = new RegExp(/\d+/);
        const s1 = resraw.toString();
        const a2 = s1.match(re);
        const s2 = a2[0];
        const theint = parseInt(s2);
        if (theint < this.minns) return;

        const ro = { 'ns' : s2, 'dom' : domain, 'ty' : ptype, 'ipv' : ipv};

        this.theres.push(ro);
        if (++this.reci >= this.stopat) {
            console.log(JSON.stringify(this.theres, null, 2));
            process.exit();
        }
    }
}

// the raw string from my timeserver takes the form '1662349142572807324     \n' - a literal \n - does not trim() and such
// https://gist.github.com/sid24rane/6e6698e93360f2694e310dd347a2e2eb // UDP

new sock();
