module.exports = class sntpWorst {
    constructor(cli) { this.coll = cli.db('sntp4').collection('calls');   }
    async get() {
        return await this.coll.aggregate(
            [
                {$match : {'U' : {'$gte' : new Date().getTime() / 1000 - 86400}, offset : {$exists : true}}},
                {$project : {absoff : {$abs : '$offset'}}},
                {$sort : {absoff : -1}},
                {$limit : 20}
            ]
        ).toArray();  
    }
}
