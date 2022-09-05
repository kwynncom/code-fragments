var net = require('net'); // TCP
var udp = require('dgram');
const { toUSVString } = require('util');

class check8123 { // executed below

    config() {
        this.ds = {'4' : 'ipv4.kwynn.com', '6' : 'ipv6.kwynn.com'};
        this.ds = {  '6' : 'ipv6.kwynn.com'};
        this.port = 8123;
        this.minns = 1662349142572807324; // before 2022/09/05 00:21 EDT / New York - this is in the past
        this.stopat = 4;
        this.reci = 0;
        this.theres = [];
        setTimeout(() => { process.exit(); }, 5000);

    }

    constructor() {
        this.config();
<<<<<<< HEAD
        this.thePromises = [];
        this.prarm = [];
        this.oro = [];
        // this.setUDP();
        this.setTCP();
        this.prarm.forEach((f) => { 
            f(); 
        });
=======
        this.setPr();
        this.dotcp();
        this.doudp();
>>>>>>> tmp
    }

    setUDP() {

        for (const [ipv, dom] of Object.entries(this.ds)) {
            var server = udp.createSocket('udp' + ipv);
            const f  = (msg) => { return this.procRes(msg, dom, 'udp', ipv);   };
            let prr;
            const pr = new Promise((resolve) => { prr = resolve }).then((pr) => { 
                pr = f(pr); 
                return pr;
            });
            this.thePromises.push(pr);

            this.prarm.push(() => {
                    server.on('message', prr);
                    server.send('a', this.port, dom);
                }
            );
        }
    }

    setTCP() {
        for (const [ipv, dom] of Object.entries(this.ds)) this.set1tcp(dom, ipv);
    }

    set1tcp(domain, ipv) {
        
        const client = net.connect({port: this.port, host: domain}, () => {
            const f =  (netr) => { return this.procRes(res, domain, 'tcp', ipv); };
            let prr;
            const pr = new Promise((resolve) => { prr = resolve }).then((res) => { 
                res = f(res); 
                return res;
            });
            this.thePromises.push(pr);
            this.prarm.push(() => {
                    client.on('data', prr);
                    client.write('a');
                }
            );
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
<<<<<<< HEAD
        this.oro.push(ro);
        return ro;
    }

    getResI() {
        let dat = Promise.all(this.thePromises).then((good) => 
        {
            const good1 = 1;
        }, (bad) => {
            const bad1 = 1;
        });
        return dat;
    }

    getRes() {
        const o = this.getResI();
        const json = JSON.stringify(this.oro);
        return json;
    }

=======

        this.theres.push(ro);
        if (++this.reci >= this.stopat) {
            this.theres = JSON.stringify(this.theres, null, 2);
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
>>>>>>> tmp
}

const o = new sock();

exports.handler = o.lam;

if (!process.env.AWS_LAMBDA_FUNCTION_NAME) o.lam();

// GOT IT, with async! 5:30am 9/5/2022

<<<<<<< HEAD
const o = new check8123();

const finaljs = o.getResI().then((x) => {
    const good503 = 1;
});

exports.handler = async (event) => { // AWS Lambda return
    const response = {
        statusCode: 200,
        headers: {
            'Content-Type': 'application/json',
        },
        body: finaljs,
    };
    return response;
};

if (!process.env.AWS_LAMBDA_FUNCTION_NAME) console.log(finaljs);
=======
>>>>>>> tmp
