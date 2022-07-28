<?php

function ht10($theps) {
	$ht = '';
	$ht .= file_get_contents(__DIR__ . '/top.html');
	echo($ht);
	require_once('frag10.php');
	
}