const http = require('http');
const url = require('url');

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
      // var c = client.db('qemail').collection('usage');
      self.client = client;
    });  
  }

  async doq(req) {

    const o = url.parse(req.url, true).query;
    const coll = this.client.db(o.db).collection(o.coll);
    const  res = JSON.stringify(await coll.find().limit(5).toArray());
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

new mongoHello();