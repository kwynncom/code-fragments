<?php


$private = openssl_pkey_new(['private_key_bits' => 2048]);
$pubpem = openssl_pkey_get_details($private)['key'];
file_put_contents('pub.pem', $pubpem);
$privo;
openssl_pkey_export($private, $privo);
file_put_contents('PRIVATE.txt', $privo);
