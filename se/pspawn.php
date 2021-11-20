<?php

// https://hotexamples.com/examples/-/-/posix_setsid/php-posix_setsid-function-examples.html

spawn();

function spawn()
 {
     function thread_shutdown()
     {
         posix_kill(posix_getpid(), SIGHUP);
     }
     if ($pid = pcntl_fork()) {
		 sleep(10);
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
		 sleep(500);
         return;
     }
     // re-parent child to kernel
     if ($pid = pcntl_fork()) {
		 sleep(500);
         return;
     }
     // now in daemonized downloader
     // download stuff
	 
	$pc = 'sleep 125 > /dev/null &';
	// echo($pc);
	// exec($pc);
	// sleep(2);
	file_put_contents('/tmp/hi',date('r'));
	echo('hi');
	sleep(300);
	 
     return;
 }
 