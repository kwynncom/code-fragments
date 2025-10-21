<?php

require_once('config.php');
require_once('/var/kwynn/hours/PRIVATE_config.php');

class hoursPostCl {

    const sfx = 'utils/postRcv.php?post=1';

    const urlBase = 'https://kwynn.com/t/25/10/hours/';
    // const urlBase     = 'http://' . hoursIntf::host . ':' . hoursIntf::port . '/';
    const url = self::urlBase . self::sfx;

    public static function post(array $data) : array {
	if (!$data || !is_array($data)) return [];

	if (self::statusKey() === 'dev' && !iscli()) return [];

	$now = time();
	$res = self::postDo($data); 
	$ret = [];


	foreach($data as $proj => $a) {
	    $proj = $a['project'];
	    if (strpos($res, $proj . 'OKDB') === false) continue;

	    $a['posted'] = [self::statusKey() => $now];
	    $ret[$proj] = $a;
	}

	return $ret;
    }

    public static function statusKey() : string {
	if (strpos(self::url, hoursIntf::host) !== false) return 'dev';
	return 'live';
    }

    public static function postDo($data) {

	if (isrv('post')) return;
	if (!$data) return;

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
	curl_close($ch);
	return $response;
    }
}
