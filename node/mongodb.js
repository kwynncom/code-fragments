const http = require('http');
var MongoClient = require('mongodb').MongoClient;
var mongoConnURL = 'mongodb://localhost/';

const hostname = '127.0.0.1';
const port = 3000;

class mongoHello {
  constructor() {
    this.htserver();
    this.mongo10(); 
  }

  mongo10() {
    MongoClient.connect(mongoConnURL, function(err, client) {

      var db = client.db('qemail');

      db.collection('usage').find()
      .toArray((err, results) => {
          if(err) throw err;
  
          results.forEach((value)=>{
              console.log(value);
          });
      })
  });  
  }

  htserver() {
    const server = http.createServer((req, res) => {
      res.statusCode = 200;
      res.setHeader('Content-Type', 'text/plain');
      this.mongo10();
      res.end('Hello World - approaching MongoDB');
    });

    server.listen(port, hostname, () => {
      console.log(`Server running at http://${hostname}:${port}/`);
    });
} // func
} // class

new mongoHello();