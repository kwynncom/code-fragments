var net = require('net');

class sock {
    constructor() {
        this.client = net.connect({port: 8123, host: 'kwynn.com'}, () => {
            this.client.on('data', (dat) => { console.log(dat); });
            this.client.write('a', () => { this.onret(); });
        
        });
    }

    onret() {
        const r = this.client.read();
        return;
    }

}

new sock();
