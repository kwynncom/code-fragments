<?php

class machine_id_validity {

public static function get($rin) {
    kwas(isset($rin['isAWS']), 'isAWS not set - mid eval');
    if (!$rin['isAWS']) {
	kwas(isset($rin['private_field_count']), 'private count unset machine id eval');
	kwas(      $rin['private_field_count'] >= 2, 'insufficent private count machine id eval');
	if ($rin['hash_of_hash'] === '0d2b4b0bd6a31e756ec58ca4b2e5751c9c4e698315391812312738eabb4aeb6d') {
	    $mid = 'kwMIDl1';
	    self::testOut($mid);
	    return $mid;
	}
	kwas(0, 'cannot ID machine - 554');
    }
    
    if (1 && $rin['hash_of_hash'] === '2fec1a8af7ddfa5d04007569f984739c3884a97b8301a87b4868191618bf9dd3') {
	$mid = 't3-1';
	self::testOut($mid, $rin);
	return $mid;
    }
    
    $mid = self::altFormAWS($rin);
    self::testOut($mid, $rin);
    return $mid;
}

private static function testOut($mid, $rin = false) {
    echo($mid . " = assigned MID\n");
    if ($rin) self::altFormAWS($rin);
    
}

private static function altFormAWS($rin) {
    $alt = substr($rin['board_asset_tag'], 0, 7);
    return $alt;
    
}

}