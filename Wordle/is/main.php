<?php

require_once('is.php');


$srcp = '/tmp/w/*.png';
$dst  = '/tmp/w/Wordle';

$files = glob($srcp);
if (!$files) {
    echo "No files in /tmp/w/Wordle/\n";
    exit;
}

foreach ($files as $file) {
    $ok = WordleColorPalette::isWordleImage($file);
    $act = $ok === true ? 'MOVED' : 'SKIP ';

    if ($ok === true) {
        $target = $dst . '/' . basename($file);
        rename($file, $target); // ()@@(@(@(@(@*$#!!!!
    }

    echo "[$act]: " . ($ok === true ? basename($file) : '') . ($ok !== true ? ' because ' . $ok : '') . PHP_EOL;
}

