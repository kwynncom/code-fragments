const net  = require('net');
const kwuf = require('./utils.js');
const baseclf = require('./base.js');
const kwas = kwuf.kwas;
const time = kwuf.time;
const cl   = kwuf.cl;
const islam = kwuf.islam;
const ck8123Base = baseclf.ck8123Base;

class sock extends ck8123Base {

    constructor() {
        super();
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
            this.stopat++;
        }
    }

    dotcp() {
        for (const [ipv, dom] of Object.entries(this.ds)) this.do1tcp(dom, ipv);
    }

    do1tcp(domain, ipv) {
        const client = net.connect({port: this.port, host: domain}, () => {
            client.on('data', (res) => { this.procRes(res, domain, 'tcp', ipv); });
            client.write('a');
            this.stopat++;
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

        if (!islam()) cl(ro);

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

        if (!islam()) {
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

    if (!islam()) {
        console.log(JSON.stringify(dat, null, 2));
        process.exit();
    }

    const response = {
        statusCode: 200,
        'Content-Type': 'application/json',
        body: dat,
    };
   
    return response;
};

if (!islam()) myf();

exports.handler = myf;
