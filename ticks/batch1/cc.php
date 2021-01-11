<?php

for($i=0; $i < 100; $i++) {
    $cmd  = 'chronyc ';
    if (1) $cmd .= 'waitsync';
    else   $cmd .= 'tracking';
    
    $b = nanotime();
    echo(shell_exec($cmd));
    $e = nanotime();
    // echo(' ' . number_format($e - $b) . "\n"); 
    
}

/* results: tracking is not vastly slower than waitsync, but tracking is less consistent.
 * The values are very roughly 3.2ms for waitsync and perhaps 4.2 for chronyc
 * 
 * The system offset becomes very, very stable after some period of time that I haven't defined yet.  Stable enough that after 
 * several hours one does not see any change with several runs (10).  And then over several minutes I see 42 to 48 ns offset.
 * 
 * No change over 100 runs, now up to 49 ns offset
 * 
 * Actually, the "stability" may be only because my system hasn't synced in a while.
 */