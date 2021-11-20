<?php

require_once('/opt/kwynn/kwutils.php');

class serviceize {
	
	const basep = '/tmp/kwsrvize_';
	
	public static function doit($cmd) {
		new self($cmd);
	}
	
	private function __construct($cmd) {
		$this->exCmd = $cmd;
		$this->do10();
		$this->do20();
	}
	
	private function do20() {
		$f = $this->ifi;
		$this->loob = $lo = new sem_lock($f);
		try {$lo->lock();
			ignore_user_abort(true);
			// $pc =  'bash ' . __DIR__ . '/size.bash > /dev/null';
			$pc = 'sleep 116 > /dev/null &';

			
			if (pcntl_fork() === 0) {
				echo($pc);
				exec($pc);
	
			// echo("\n" . 'setsid = ' . "$ss\n");
			
			fclose(STDIN);
			fclose(STDOUT);
			fclose(STDERR);

			$ss = posix_setsid();
			sleep(500);
			// if ($ss === -1) die('bad setsid()');
			}
			else sleep(2);
			// exit(0);
			// sleep(20);
		//	$pid = trim(shell_exec($pc)); $pid = intval($pid); kwas($pid >= 1, 'bad pid');
		//	file_put_contents($f, $pid);
		} catch(Exception $ex) { } finally { $lo->unlock(); if (isset($ex)) throw $ex;	}
		return;		
	}
	
	private function do10() {
		$a = preg_split('/\s+/', $this->exCmd);
		if (count($a) === 1) $bch = $a[0];
		else if ($a[0] === 'php') $bch = $a[1];
		else $bch = $a[0];
		$bch = preg_replace('/[^A-Za-z_0-9]/', '_', $bch);
		$bf = self::basep . get_current_user() . '_' . $bch . '.pid';
		if (!file_exists($bf)) file_put_contents($bf, '');
		$this->ifi = $bf;
	}
}

if (didCLICallMe(__FILE__)) serviceize::doit('sleep 128');
