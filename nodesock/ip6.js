var net = require('net'); // TCP

const myf = async (event) => {

    let onfin;
    const thepr = new Promise((resolve) => {    onfin = resolve;    });

    let prres;
    let prhtres;

    const client = net.connect({port: 8123, host: 'ipv6.kwynn.com'}, () => {
        client.on('data', (res) => { 
            const response = {
                statusCode: 200,
                'Content-Type': 'application/json',
                body: res,
            };

            prres = res;
            prhtres = response;
            onfin(response);
            return response;
        });
        client.write('a');
    });

    await thepr;


    if (!process.env.AWS_LAMBDA_FUNCTION_NAME) {
        console.log(prres.toString());
        process.exit();
    }

    return prhtres;

}

exports.handler = myf;

if (!process.env.AWS_LAMBDA_FUNCTION_NAME) myf();
