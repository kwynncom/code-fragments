<?php

function getHTLIUserOrDie() {

	$https = kwifs($_SERVER, 'HTTPS'); kwas($https, 'must use SSL / TLS / web security cert'); unset($https);
	$user  = kwifs($_SERVER, 'PHP_AUTH_USER'); kwas($user && is_string($user) && preg_match('/^\S/', $user), 
						'no or invalid username - HTTP / Apache password');
	
	return $user;
}

