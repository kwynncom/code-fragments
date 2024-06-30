var MongoClient = require('/usr/local/lib/node_modules/mongodb').MongoClient;
var url = "mongodb://localhost:27017/";

MongoClient.connect(url, function(err, dbin) {
    if (err) throw err;
    var db = dbin.db("wsal");
    db.collection('lines').findOne({}, function(err, result) {
        if (err) throw err;
        console.log(result.line);
        process.exit();
    });
    ignore = 2;
});
