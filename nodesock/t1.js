var net = require('net');

class sock {
    constructor() {
        this.do10();
    }

    do10() {

        const ds = ['ipv4.kwynn.com', 'ipv6.kwynn.com'];

        setTimeout(() => { process.exit(); }, 1000);

        for (let i=0; i < ds.length; i++) {
            const client = net.connect({port: 8123, host: ds[i]}, () => {
                client.on('data', (dat) => { 
                    console.log(dat.toString().trim());
                });
                client.write('a');
            });
        }

    }
}

new sock();
