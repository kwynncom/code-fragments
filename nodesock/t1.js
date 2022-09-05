var net = require('net');

class sock {
    constructor() {
        this.do10();
    }

    do10() {

        const ds = ['ipv4.kwynn.com', 'ipv6.kwynn.com'];

        let reci = 0;

        for (let i=0; i < ds.length; i++) {
            const client = net.connect({port: 8123, host: ds[i]}, () => {
                client.on('data', (dat) => { 
                    const theint = parseInt(dat.toString().trim());
                    if (theint < 1662349142572807324) return;
                    console.log(theint);
                    if (++reci === ds.length) process.exit();
                });
                client.write('a');
            });
        }

    }
}

new sock();
