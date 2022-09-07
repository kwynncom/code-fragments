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
    }

    dotcp() { 
        for (const [ipv, dom] of Object.entries(this.ds)) {
            const cb = (res) => { this.procRes(res, dom, 'tcp', ipv); };
            this.do1tcp(dom, this.port, ipv, cb);  
            this.stopat++;
        }
    }

    do1tcp(domain, port, ipv, cb) {
        const client = net.connect({port: port, host: domain}, () => {
            client.on('data', cb);
            client.write('a');
        });
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
