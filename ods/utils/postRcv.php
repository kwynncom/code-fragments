<?php



if (true) {
require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/hours/PRIVATE_config.php');
require_once('db.php');

header('Content-Type: text/plain');


class postReceiveCl {

    public function __construct() {

	try {
	    ob_start();
	    $dat = $this->do10();
	    echo('dat');
	    odsDBCl::put($dat, true);
	    echo(ob_get_clean());
	    
	} catch (Throwable $ex) {
	    $this->htCrash($ex);
	}
    }

    private function do10() : array {

	$j = file_get_contents('php://input');
	kwas($j && is_string($j) && strlen($j) >= 10 && strlen($j) < 300, 'bad data err # 010821');
	echo($j);
	$d = json_decode($j, true);
	kwas($d && is_array($d) && count($d) >= 1, 'bad data err # 010923');
	kwas(isset($d['secret']), 'secret not set err # 023328');
	$s =       $d['secret'];
	kwas(
		is_string($s)
		&& strlen($s) > 10
		, 'no valid secret err # 011025 at ' . date('r'));
	kwas ($s === KW_HOURS_PRIVATE_SECRET, 'incorrect secret err # 011127');
	unset($d['secret'], $s);

	return $d;
    }

    private function htCrash(Throwable $ex) {
	http_response_code(400);
	echo(ob_get_clean());
	echo($ex->getMessage());
	exit(-1);
    }

}

if (didCLICallMe(__FILE__) || isrv('post')) {
    echo('hi');
    new postReceiveCl();
}

} // if true / false
else {
    echo('hi');
}