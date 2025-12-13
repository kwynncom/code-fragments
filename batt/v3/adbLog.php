<?php

declare(strict_types=1);

use React\EventLoop\LoopInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\ReadableStreamInterface;
use React\EventLoop\Loop;
use ReactLineStream\LineStream;

final class ADBLogReaderCl
{

    const adbService = 'BatteryService';
    const cmd = 'adb logcat ' . self::adbService . ':D *:S 2>&1';

    private object $lines;
    private object $loop;
    private mixed  $file;

    private readonly mixed $cb;

    private function bufferTrueSend() {
	static $lat = 0;

	$now = time();

	if ($now - $lat < 7) {
	    belg('discarding multiple positives');
	    return;
	}
	($this->cb)(true);
	$lat = $now;
    }

    private function sendStatus(bool $setto) {

	static $prev;

	belg('logcat status is now ' . ($setto ? 'true' : 'false'));
	if ((!$setto) || ($prev !== true)) { 
	    ($this->cb)($setto); 
	} else if ($setto) $this->bufferTrueSend();
	
	$prev = $setto;
	
    }

    private function checkDat(string $line) {

	// belg($line);

	if (strpos($line, self::adbService) !== false) {
	    $this->sendStatus(true);
	}
	if (trim($line) === '- waiting for device -') {
	    belg($line);
	}

    }


    public function __construct(callable $cb = null) {
	$this->cb = $cb;
	$this->reinit();
    }

    public function __destruct() { $this->close(); }


    private function reinit() {

	static $i = 0;

	if ($i++ > 0) {
	    beout('');
	    belg('blanking due to *re*-init of :' . self::cmd);
	} else $this->close();

	$this->init();
    }

    private function init(

    ) {

 	belg(self::cmd);
	$this->loop = Loop::get();

        $this->file = popen(self::cmd, 'r');
        if (!$this->file) {
            throw new \RuntimeException('Cannot open stream: ' . self::cmd);
        }

        $resourceStream = new ReadableResourceStream($this->file, $this->loop);
	$this->lines = new LineStream($resourceStream);
        $this->lines->on('data' , function (string $line) { $this->checkDat($line); });
	$this->lines->on('close', function (		) { $this->reinit();	    });
        $this->loop->run();

    }


    public function close(string $ev = 'unknown event'): void        { 

	$this->sendStatus(false);

	if (isset($this->loop)) $this->loop->stop();
	unset($this->loop);

	belg(self::cmd . ' event ' . $ev);
	if (isset($this->lines)) {
	    $this->lines->close();
	    unset($this->lines);
	}

	if (isset($this->file) && is_resource($this->file) && 
		   ($meta = @stream_get_meta_data($this->file)) &&
		   !empty($meta['stream_type'])) pclose($this->file); 

	
	unset($this->file);

    }
}

if (didCLICallMe(__FILE__)) {
    new ADBLogReaderCl();
}