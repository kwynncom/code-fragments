const kwuf = require('./utils.js');
const kwas = kwuf.kwas;
const time = kwuf.time;
const islam = kwuf.islam;
const cl   = kwuf.cl;

class ck8123Base {
    config() {
        this.ds = {'4' : 'ipv4.kwynn.com', '6' : 'ipv6.kwynn.com'}; // IPv6 still has trouble
        // this.ds = {'4' : 'ipv4.kwynn.com'}; // , '6' : 'ipv6.kwynn.com'};
        this.port = 8123;
        this.minns = (time() - 120000) * 1000000;
        kwas(this.minns > 1662528661721266926); // a time in the past; sanity check
        this.stopat = 0;
        this.reci = 0;
        this.theres = [];
        if (!islam()) setTimeout(() => { process.exit()}, 3000 );
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


}

module.exports.ck8123Base = ck8123Base;
