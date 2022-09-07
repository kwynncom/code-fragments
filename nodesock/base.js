const kwuf = require('./utils.js');
const kwas = kwuf.kwas;
const time = kwuf.time;
const islam = kwuf.islam;

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
}

module.exports.ck8123Base = ck8123Base;
