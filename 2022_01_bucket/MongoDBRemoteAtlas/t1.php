<?php // 2021/01/25 7:57pm

// https://github.com/domzalex/gda-events-manager

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/composer');
require_once('vendor/autoload.php');
require_once('/opt/kwynn/kwutils.php');

$cli = new MongoDB\Client(file_get_contents('/var/mongo_ad1_2021_1.txt'), [], ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']]);
$coll1  = $cli->selectCollection('kwynnTest', 'kwynnTest1');
$coll1->insertOne(['arow' => 'kwynn-8:26pm']);
$res = $coll1->findOne([], ['sort' => ['_id' => -1]]);

var_dump($res);
