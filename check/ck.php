<?php

require_once('/var/kwynn/mystery_2024_0920_1/params.php');

if (true) {


    $ch = curl_init($post['url']);
    unset(	    $post['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    
    if (true) {
	$response = curl_exec($ch);
	var_dump($response);
	curl_close($ch);
    }

}