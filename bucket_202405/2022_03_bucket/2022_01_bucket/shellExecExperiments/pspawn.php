<?php

sleep(5);

spawn();

function spawn() // https://hotexamples.com/examples/-/-/posix_setsid/php-posix_setsid-function-examples.html
 {
     function thread_shutdown()
     {
         posix_kill(posix_getpid(), SIGHUP);
     }
     if ($pid = pcntl_fork()) {
         return;
     }
     // spawn to child process
     fclose(STDIN);
     // close descriptors
     fclose(STDOUT);
     fclose(STDERR);
     register_shutdown_function('thread_shutdown');
     // zombie-proof
     if (posix_setsid() < 0) {
         return;
     }
     // re-parent child to kernel
     if ($pid = pcntl_fork()) {
         return;
     }
     // now in daemonized downloader
     // download stuff
	 
	 sleep(20);
	 
     return;
 }