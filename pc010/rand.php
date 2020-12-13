<?php

$maxRounds = 1000;
$i = 0;
$donewr = true;

do {
    if ($donewr) $rand = random_int(1,100);
    echo("Enter a number between 1 and 100, or enter 0 to quit: ");
    $entry = intval(trim(fgets(STDIN)));
    if ($entry == 0) exit(0);
    $donewr = false;
    if      ($entry == $rand)  { $donewr = true;
			       echo("You guessed it!  Let's play again!\n"); }
    else if ($entry <  $rand)  echo("The secret number is greater than $entry.  Please try again...\n");
    else if ($entry >  $rand)  echo("The secret number is less than $entry.  Please try again...\n");
    
} while(++$i <= $maxRounds);

echo("The game has been limited to $maxRounds rounds as a check against infinite loops.\n");
