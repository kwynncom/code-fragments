<?php

$key = 'test5';

if (isset($_COOKIE[$key])) {
	exit(0);

}

setcookie($key, date('H_i_s'), time() + 15);


exit(0);


