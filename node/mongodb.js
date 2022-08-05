const http = require('http');
var MongoClient = require('mongodb').MongoClient;
var mongoConnURL = 'mongodb://localhost/';

const hostname = '127.0.0.1';
const port = 3000;

class mongoHello {
  constructor() {
    this.setMongoDB();
    this.htserver();

  }

  setMongoDB() {

    const self = this;

    MongoClient.connect(mongoConnURL, function(err, client) {
      var db = client.db('qemail');
      self.dbo = db;

    });  
  }

  async getMongoRes() {
    const dbrr = await this.dbo.collection('usage').find().toArray();
    return JSON.stringify(dbrr);
  }

  async doHTr(req, res) {
    res.statusCode = 200;
    res.setHeader('Content-Type', 'text/plain');
    const mr = await this.getMongoRes();
    res.end(mr);
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

new mongoHello();