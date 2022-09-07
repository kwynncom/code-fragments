const net  = require('net');
const lookupclf = require('./lookup.js');
const lookup = lookupclf.lookup;

class ck8123TCP {

    constructor(domain, port, ipv, cb) {
        this.do1tcp(domain, port, ipv, cb);
    }

    async do1tcp(domain, port, ipv, cb) {

        const ip = await lookup(domain);

        const client = net.connect({port: port, host: ip}, () => {
            client.on('data', cb);
            client.write('a');
        });
    }

}

module.exports.ck8123TCP = ck8123TCP;