<?php

require_once('config.php');

class hoursPostCl {

    // const urlProd = 'https://kwynn.com/t/25/10/hours/utils/postRcv.php';
    const url     = 'http://' . DEV_HOST . ':' . DEV_PORT . '/utils/postRcv.php';

     public static function post(array $data) {
	$jsonData = json_encode($data);

	$ch = curl_init(self::url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
	    'Content-Type: application/json',
	    'Content-Length: ' . strlen($jsonData)
	]);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

	$response = curl_exec($ch);

	if (curl_errno($ch)) {
	    echo 'Error: ' . curl_error($ch);
	} else {
	    echo $response;
	}

	curl_close($ch);
	
	return;
    }
}
