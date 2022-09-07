const net  = require('net');

class ck8123TCP {

    constructor(domain, port, ipv, cb) {
        this.do1tcp(domain, port, ipv, cb);
    }

    do1tcp(domain, port, ipv, cb) {
        const client = net.connect({port: port, host: domain}, () => {
            client.on('data', cb);
            client.write('a');
        });
    }

}

module.exports.ck8123TCP = ck8123TCP;