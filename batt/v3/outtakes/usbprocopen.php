<?php // Grok and I both misunderstood something.
// I thought stderr redirect wasn't working, but I was misinterpreting the output
// This might be of interest later, though.
// I did appear to work fine.
declare(strict_types=1);
require_once('utils.php');
require_once('/var/kwynn/batt/PRIVATEphones.php');
require_once('adbDevices.php');

use React\Stream\ReadableResourceStream;
use ReactLineStream\LineStream;
use React\EventLoop\Loop;

class usbMonitorCl {
    const cmd = 'udevadm monitor -s usb 2>&1';

    private readonly object $loop;
    private readonly object $noti;

    private $processResource = null;
    private $pipes = null;
    private $lineStream = null;

    public function __construct(object $noti) {
        $this->noti = $noti;
        $this->init();

        if ($this->lsusb()) {
            $this->noti->notify('usb', 'lsusb', true);
        }
    }

    private function checkDat(string $l) {
        static $lat = 0;
        $check = false;

        if (strpos($l, ' add ') !== false) {
            $check = 'add';
        }
        if (strpos($l, 'KERNEL - the kernel uevent') !== false) {
            $check = 'init';
        }

        $now = microtime(true);
        if ($check) {
            if ($now - $lat < 1) return;
            belg($l);
            $lat = $now;
            $this->noti->notify('usb', $check);
        }
    }

    private function init() {
        $this->loop = Loop::get();
        belg(self::cmd, true);

        $descriptors = [
            0 => ['pipe', 'r'], // stdin - unused
            1 => ['pipe', 'w'], // stdout (will include stderr due to 2>&1)
            2 => ['pipe', 'w'], // stderr - we redirect it in the command, but still create the pipe to avoid inheritance issues
        ];

        $process = proc_open(self::cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            throw new RuntimeException('Failed to start command: ' . self::cmd);
        }

        $this->processResource = $process;
        $this->pipes = $pipes;

        // Close unused stdin
        fclose($pipes[0]);

        // Close the stderr pipe immediately (since we redirected 2>&1, nothing should go here)
        fclose($pipes[2]);

        // Create readable stream only from stdout (which now contains both stdout and stderr)
        $stdoutStream = new ReadableResourceStream($pipes[1], $this->loop);

        // Set up line processing on the merged output
        $this->lineStream = new LineStream($stdoutStream);
        $this->lineStream->on('data', function (string $line) {
            $this->checkDat($line);
        });
    }

    public function close() {
        if (isset($this->lineStream)) {
            $this->lineStream->close();
        }

        if (isset($this->pipes)) {
            foreach ($this->pipes as $pipe) {
                if (is_resource($pipe)) {
                    fclose($pipe);
                }
            }
            $this->pipes = null;
        }

        if (isset($this->processResource) && is_resource($this->processResource)) {
            proc_terminate($this->processResource);
            proc_close($this->processResource);
            $this->processResource = null;
        }
    }

    private static function lsusb() : bool {
        $b = microtime(true);
        $s = shell_exec('timeout -k 0.1 0.15 lsusb');
        $e = microtime(true);

        belg('l-susb took ' . sprintf('%0.3f', $e - $b) . 's');

        if (!$s || !is_string($s)) {
            return false;
        }

        foreach (KWPhonesPRIVATE::list as $r) {
            if (strpos($s, $r['vidpid']) !== false) {
                belg('usb found specific device');
                return true;
            }
        }

        return false;
    }
}