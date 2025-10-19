<?php 

    require_once('/opt/kwynn/kwutils.php');

function indexF() {
    if (!isrv('post')) {
	 if (false && $_SERVER['SERVER_PORT'] === '8001') {
	    exit(0);
	 }
	 require_once(__DIR__ . '/www/index.php');
	 return;
    }
    else {
	require_once(__DIR__ . '/utils/postRcv.php');
	new postReceiveCl();
	return;
    }
}

indexF();
   