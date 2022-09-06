var net = require('net'); // TCP
// var udp = require('dgram');
/* 2022/09/05 06:06

I am getting some results on Lambda.  

Lambda doesn't seem to have UDP, or something is different.  It is also having trouble with ipv6.  About half my queries 
fail, too.  It's a start.

*/

class sock { // executed below

    config() {
        // this.ds = {'4' : 'ipv4.kwynn.com', '6' : 'ipv6.kwynn.com'}; // IPv6 still has trouble
        this.ds = {'4' : 'ipv4.kwynn.com'}; // , '6' : 'ipv6.kwynn.com'};
        this.port = 8123;
        this.minns = 1662349142572807324; // before 2022/09/05 00:21 EDT / New York - this is in the past
        this.stopat = 2;
        this.reci = 0;
        this.theres = [];
        // setTimeout(() => { process.exit(); }, 5000); // This messes up Lambda; This REALLY messes up Lambda.  
        // It causes execution to be many times slower

    }

    constructor() {
        this.config();
        this.setPr();
        this.dotcp();
        this.doudp();
    }

    doudp() {

        if (typeof udp === 'undefined') return;

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
            this.onfin();
            return;
        }
    }

    getResI() { return this.theres; }

    setPr() {
        this.thepr = new Promise((resolve) => { 
            this.onfin = resolve;
        }).then(() => {
                return this.theres;
            }
        );
    }

    async getRes() { return await this.thepr; }

    async lam() {

        const dat = await this.getRes();

        if (!process.env.AWS_LAMBDA_FUNCTION_NAME) {
            console.log(dat);
        }

        const response = {
            statusCode: 200,
            headers: {
                'Content-Type': 'application/json',
            },
            body: dat,
        };
        return response;
    }
}

const myf = async (event) => {

    const o = new sock();
    const dat = await o.getRes();

    if (!process.env.AWS_LAMBDA_FUNCTION_NAME) {
        console.log(JSON.stringify(dat, null, 2));
    }

    const response = {
        statusCode: 200,
        'Content-Type': 'application/json',
        body: dat,
    };
    return response;
};

if (!process.env.AWS_LAMBDA_FUNCTION_NAME) myf();

exports.handler = myf;
