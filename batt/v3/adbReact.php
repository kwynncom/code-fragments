<?php

declare(strict_types=1);

// require_once('utils.php');

use React\EventLoop\LoopInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\ReadableStreamInterface;
use React\EventLoop\Loop;
use ReactLineStream\LineStream;  // ← Correct import, but installed from Tsufeki

final class ADBLogReaderCl
{

    const adbService = 'BatteryService';

    private readonly object $lines;
    private readonly object $loop;
    private readonly mixed  $file;

    private readonly mixed $cb;


    private function setstat(bool $setto) {
	belg('logcat to ' . ($setto ? 'true' : 'false'));
	($this->cb)($setto);
    }

    private function checkDat(string $line) {
	if (strpos($line, self::adbService) !== false) {
	    $this->setstat(true);
	    if ($this->cb ?? false) ($this->cb)(true);
	}
	if (trim($line) === '- waiting for device -') {
	    belg($line);
	}

    }

    private function getFile() {
	return 'adb logcat ' . self::adbService . ':D *:S 2>&1';
    }

    public function __construct(callable $cb = null) {
	$this->cb = $cb;
	$this->init();
	$this->use();
    }

    public function __destruct() { $this->close(); }

    public function sigintHandler(int $signal) {
	    echo "\nCaught SIGINT (Ctrl+C) – shutting down gracefully…\n";
	    // Loop::get()->stop();    
	    $this->close('control-C / SIGINT'); 
    }

    private function init(

    ) {

        $filename = $this->getFile();
	belg($filename);
	$this->loop = Loop::get();
	$this->loop->addSignal(SIGINT, [$this, 'sigintHandler']);

        $this->file = popen($filename, 'r');
        if (!$this->file) {
            throw new \RuntimeException("Cannot open stream: $filename");
        }

        $resourceStream = new ReadableResourceStream($this->file, $this->loop);
	$this->lines = new LineStream($resourceStream);
    }


    public function use(): void
    {
        $this->lines->on('data', function (string $line) {
            $this->checkDat($line);
        });

	$this->lines->on('close', function(string $ev = 'closeunk') {    $this->close($ev);	} );
	$this->lines->on('end', function  (string $ev = 'endunk') {    $this->close($ev);	} );

        echo "Now reading – Ctrl+C to stop\n\n";

        $this->loop->run();
    }

    public function lines(): LineReader { return $this->lines; }
    public function pause(): void        { $this->lines->pause(); }
    public function resume(): void       { $this->lines->resume(); }
    public function close(string $ev = 'unknown event'): void        { 

	$this->setstat(false);

	Loop::get()->removeSignal(SIGINT, [$this, 'sigintHandler']);
	
	Loop::get()->stop();

	belg('logcat ' . $ev);
	if (isset($this->lines)) $this->lines->close();

	if (isset($this->file) && is_resource($this->file) && 
		   ($meta = @stream_get_meta_data($this->file)) &&
		   !empty($meta['stream_type'])) pclose($this->file); 
    }
}

if (didCLICallMe(__FILE__)) {
    new ADBLogReaderCl();
}