const net  = require('net');

const myf = async (event) => {
    
    const ipv4 = '34.193.238.16';
    const ipv6 = '2600:1f18:23ab:9500:7a93:a206:f823:20c3';
    const dom = 'kwynn.com';
    
    let onfin;
    const thepr = new Promise((resolve) => { onfin = resolve; });
    
    let cs = 'nothing';
    
    const client = net.connect({port: 8123, host: dom, family: 4}, () => {
        cs = 'connected';
        onfin(cs);
    });
    
    await thepr;
    
    if (!process.env.AWS_LAMBDA_FUNCTION) {
        console.log(cs);
        if (!process.env.AWS_LAMBDA_FUNCTION_NAME)             process.exit();
    }

    // TODO implement
    const response = {
        statusCode: 200,
        body: JSON.stringify(cs),
    };
    return response;
};

if (!process.env.AWS_LAMBDA_FUNCTION_NAME) {
    myf();

}

exports.handler = myf;
