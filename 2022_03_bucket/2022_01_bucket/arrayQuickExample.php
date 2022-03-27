<?php

$people = [
	['name' => 'Alex', 'species' => 'elf', 'age' => 324],
        ['name' => 'Bob','species' => 'ogre','age' => 12]
];

foreach($people as $i => $dat) {
    echo("person number (0 indexed): $i: ");
    foreach($dat as $key => $value) echo("key $key value $value");
    echo("\n");
}