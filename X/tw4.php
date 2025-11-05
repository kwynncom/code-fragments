<?php

require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/X/XAPIKeys.php');

use Abraham\TwitterOAuth\TwitterOAuth;

$connection = new TwitterOAuth(...getXAPIArgs());

$tweetIds = ['1985620222137553392', ];
$parameters = [
    'ids' => implode(',', $tweetIds),
    'tweet.fields' => 'created_at,public_metrics',
    'user.fields'   => 'username,name,verified,profile_image_url,description,created_at',
];

if (false) { 
    $response = $connection->get('tweets', $parameters);
}

// $headers = $connection->getLastResponseHeaders();
// echo "Remaining: " . ($headers['x-rate-limit-remaining'] ?? 'N/A') . "\n";


var_dump($response);

