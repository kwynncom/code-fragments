const dns = require('node:dns');

async function lookup(domain) {

    let onfin;
    const thepr = new Promise((resolve) => { onfin = resolve; });
        
    let theres;

    dns.lookup(domain, null, (err, address, family) =>  {
       theres = address;
       if (!theres) theres = 'returned but nothing';
       if (err) theres = err;
       onfin(theres);
    });

    await thepr;

    return theres;
}

module.exports.lookup = lookup;
