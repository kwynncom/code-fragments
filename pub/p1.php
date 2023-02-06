<?php

$prf = 'PRIVATE.txt';
$puf = 'pub.pem';
$bits = 4096;

if (1) {
	$private = openssl_pkey_new(['private_key_bits' => $bits]); unset($bits);
	$pubpem = openssl_pkey_get_details($private)['key'];
	file_put_contents($puf, $pubpem); unset($pubpem);
	openssl_pkey_export_to_file($private, $prf); unset($private);
}

$pro = openssl_pkey_get_private(file_get_contents($prf));  unset($prf);
$puo = openssl_pkey_get_public (file_get_contents($puf)); unset($puf);

$secret = 'I am the secret 5.';
openssl_public_encrypt ($secret, $cipherText, $puo); unset($secret, $puo);
openssl_private_decrypt($cipherText, $plainText, $pro); unset($pro, $ciperText);
echo($plainText);
