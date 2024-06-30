printjson(db.getCollection('seq').aggregate([ {$match : {'_id' : {'$ne' : 'base'}}}, {$group : {_id : 'series', 'sum' : {'$sum' : '$seq'}, 'count' : {'$sum' : 1}}}]).toArray())

