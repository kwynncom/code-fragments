// 01:27 - working version, if you have enough fake money in the account
// 01:26 - both 01:15 and this do NOT work, intentionally to show the error
// 2022/08/19 01:15 EDT / New York / Atlanta / a county north of Atlanta

// https://tl1.knightsofcode.net/t/7/11/blog.html#eAlpacaHW
const Alpaca = require("@alpacahq/alpaca-trade-api"); // https://alpaca.markets/docs/trading/getting-started/
const kwutils = require('/opt/kwynn/js/utils.js'); // https://github.com/kwynncom/kwynn-php-general-utils/blob/master/js/utils.js
const kwas = kwutils.kwas;
const creds = require('/var/kwynn/private/alp.js'); // real creds
const credsExampleM = require('./public_example_creds.js'); // a sort-of running example
const credsExV = credsExampleM.publicSampleCreds; // testing the example; demonstrating the example
kwas(Object.keys(credsExV).length === 3); // continued
kwas(credsExV.paper === true); // cont.

const alpaca = new Alpaca(creds.creds202208_1); // use real creds

async function example() {

  alpaca.getAccount().then((account) => { console.log("Current Account:", account);  });

  const buyParams = {
    symbol: "BTC/USD",
    qty: 1,
    side: "buy",
    type: "market",
    time_in_force: "gtc", // "day" is INVALID FOR CRYPTO!!
  };
 
  const pit = alpaca.createOrder(buyParams).then((order) => {
        console.log("Order details: ", order);
  }, (error) => {
        const ignore1914 = 1914; // Make sure I have a line for a breakpoint
  });
} // example

async function exampleWrapper() {
  await example();
}

exampleWrapper();
