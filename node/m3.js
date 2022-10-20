const http = require('http');
const url = require('url');
const MongoClient = require('mongodb').MongoClient;
const mongoConnURL = 'mongodb://localhost/';
const hostname = '127.0.0.1';
const port = 3000;

const sntpWorst = require('./myqueries/sntp_worst.js');

class myMongoDBServer {
  constructor() {
    const self = this;
    MongoClient.connect(mongoConnURL, function(err, client) { self.sntpWoO = new sntpWorst(client); }); 
    this.initHTServer();
  }

 async doq(req) {   return JSON.stringify(await this.sntpWoO.get());  }

 async doHTr(req, res) {
    const json = await this.doq(req);
    res.statusCode = 200;
    res.setHeader('Content-Type', 'application/json');
    res.end(json);
  }

  initHTServer() {
    const self = this;
    const server = http.createServer((req, res) => { self.doHTr(req, res);   });
    server.listen(port, hostname, () => { console.log(`Server running at http://${hostname}:${port}/`);  });
  } // func
} // class

new myMongoDBServer();

