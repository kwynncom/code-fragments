<?php

require_once('utils.php');

$gs = ['arise', 'glint', 'build', 'quill'];
$word = 'quill';

echo($word . ' is the solution' . "\n");

$fs = [];

foreach($gs as $g) {
    $cmp  = cmp($word, $g);
    $poss = getPossible($g, $cmp);
    echo($g . ' is the guess' . "\n");
    echo(json_encode($poss, JSON_PRETTY_PRINT) . "\n");
    echo(count($poss). " possible (non-culm) for guess $g and word $word\n***\n");
}

foreach($gs as $g) {

    $cmp  = cmp($word, $g);
    $fs[] = [$g => $cmp];
    $poss = getPossibleCulm($fs);
    echo($g . ' is the guess' . "\n");
    echo(json_encode($poss, JSON_PRETTY_PRINT) . "\n");
    echo(count($poss). " possible (culm) for guess $g and word $word\n***\n");
}

echo($word . ' is the solution' . "\n");





