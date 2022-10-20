const http = require('http');
const url = require('url');
const MongoClient = require('mongodb').MongoClient;
const mongoConnURL = 'mongodb://localhost/';
const hostname = '127.0.0.1';
const port = 3000;

const sntpWorst = require('./myqueries/sntp_worst.js');

class myMongoDBServer {
  constructor() {
    this.setMongoDBBase();
    this.htserver();

  }



  setMongoDBQs() {
    this.sntpWoO = new sntpWorst(this.client);

}   

  setMongoDBBase() {
    const self = this;
    MongoClient.connect(mongoConnURL, function(err, client) { 
      self.client = client;
      self.setMongoDBQs();    
    }); 
    
    return;
  }

  async doq(req) {

    const a = await this.sntpWoO.get();

    const  res = JSON.stringify(a);
    return res;
  }

  async doHTr(req, res) {

    const json = await this.doq(req);

    res.statusCode = 200;
    res.setHeader('Content-Type', 'application/json');
    res.end(json);
    // process.exit();
  }

  htserver() {
    const self = this;
    const server = http.createServer((req, res) => {
        self.doHTr(req, res);
    });

    server.listen(port, hostname, () => {
      console.log(`Server running at http://${hostname}:${port}/`);
    });

  } // func
} // class

new myMongoDBServer();

