<?php

require_once('cookie/location.php');

function getMapSettings() {
	$a = locSessCl::getArrFromCookie();
	if ($a) return $a;
	
}