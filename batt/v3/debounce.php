<?php

require_once('/opt/kwynn/kwutils.php');



function simulateRequest($loop) {
    static $debounceTimer = null;

    if ($debounceTimer) {
        $loop->cancelTimer($debounceTimer);
    }

    $debounceTimer = $loop->addTimer(3.0, function () use ($loop) {
        echo "Performing debounced action now!\n";
        // Do your expensive work here (e.g., DB save, email send)
        
        // Reset for next cycle
        $debounceTimer = null;
    });

    echo "Request received – debouncing...\n";
}

// Get the default event loop
$loop = Loop::get();

// Simulate 3 rapid requests
for ($i = 0; $i < 3; $i++) {
    simulateRequest($loop);
    usleep(500000); // 0.5s delay between sims
}

// Run the loop
$loop->run();