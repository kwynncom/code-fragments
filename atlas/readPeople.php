<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/composer');
require_once('vendor/autoload.php');
require_once('/opt/kwynn/kwutils.php');

$cli = new MongoDB\Client(file_get_contents('/var/mongo_ad1_2021_1.txt'), [], ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']]);
$coll1  = $cli->selectCollection('kwynnTest', 'kwynnTestInsert');
$res = $coll1->find()->toArray(); // this is an array and thus legible when viewed in debugger or var_dump'ed
var_dump($res);
