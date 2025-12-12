<?php

require_once('utils.php');

use React\EventLoop\LoopInterface;
use React\Stream\ReadableResourceStream;
use React\Stream\ReadableStreamInterface;
use React\EventLoop\Loop;
use ReactLineStream\LineStream;  // ← Correct import, but installed from Tsufeki

final class FileLineReader
{
    private readonly object $lines;
    private readonly object $loop;
    private readonly mixed  $file;

    private function getFile() {
	return 'adb logcat BatteryService:D *:S 2>&1';
    }

    public function __construct() {
	$this->init();
	$this->use();
    }

    private function init(

    ) {

        $filename = $this->getFile();
	$this->loop = Loop::get();

	$this->loop->addSignal(SIGINT, function (int $signal) {
	    echo "\nCaught SIGINT (Ctrl+C) – shutting down gracefully…\n";

	    // Your cleanup here
	    
	    $this->close('control-C / SIGINT'); 
	     
	    // $this->lines->close();   // if you have a stream
	    // $server->close();        // if you have an HTTP server
	    // file_put_contents('state.json', json_encode($state));

	    Loop::get()->stop();  // stops the loop cleanly
	});


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
            echo date('H:i:s') . ' → ' . $line . PHP_EOL;
        });

	$this->lines->on('close', function(string $ev = 'closeunk') {    $this->close($ev);	} );
	$this->lines->on('end', function  (string $ev = 'endunk') {    $this->close($ev);	} );

        echo "Now reading – Ctrl+C to stop\n\n";

        $this->loop->run();
    }

    // Optional helpers if you ever need finer control
    public function lines(): LineReader { return $this->lines; }
    public function pause(): void        { $this->lines->pause(); }
    public function resume(): void       { $this->lines->resume(); }
    public function close(string $ev = 'unknown event'): void        { 
	belg('logcat ' . $ev);
	if (isset($this->lines)) $this->lines->close();

	if (isset($this->file) && is_resource($this->file) && 
		   ($meta = @stream_get_meta_data($this->file)) &&
		   !empty($meta['stream_type'])) pclose($this->file); 
    }
}

if (didCLICallMe(__FILE__)) {
    new FileLineReader();
}