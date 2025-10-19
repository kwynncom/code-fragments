<?php

require_once('config.php');
require_once('/var/kwynn/hours/PRIVATE_config.php');

class hoursPostCl {

    // const url = 'https://kwynn.com/t/25/10/hours/utils/postRcv.php?post=1';
    // const url     = 'http://' . DEV_HOST . ':' . 8001 . '/?post=1';
    const url     = 'http://' . DEV_HOST . ':' . 8001 . '/utils/postRcv.php?post=1';

     public static function post(array $data) {

	$data['secret'] = KW_HOURS_PRIVATE_SECRET;

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
