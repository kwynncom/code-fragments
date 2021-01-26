<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/composer');
require_once('vendor/autoload.php');
require_once('/opt/kwynn/kwutils.php');


$dat = $_REQUEST['v'];

$cli = new MongoDB\Client(file_get_contents('/var/mongo_ad1_2021_1.txt'), [], ['typeMap' => ['array' => 'array','document' => 'array', 'root' => 'array']]);
$coll1  = $cli->selectCollection('kw_webapp1', 'test1');
$coll1->insertOne(['webField1' => $dat]);
$res = $coll1->findOne([], ['sort' => ['_id' => -1]]);
var_dump($res);
