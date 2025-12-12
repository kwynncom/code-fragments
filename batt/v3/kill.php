<?php

final class PidFileGuard
{
    private function __construct() {} // static only

    /**
     * Ensures only one instance runs. Kills any stale/zombie process
     * referenced in the lock file (or holding it open), then claims the lock with current PID.
     *
     * @param string $pidFile Path to the PID lock file (e.g. /var/run/myapp.pid)
     * @param bool   $forceKill If true, SIGKILL after SIGTERM fails
     * @return void
     * @throws RuntimeException on fatal errors
     */
    public static function acquire(string $pidFile, bool $forceKill = true): void
    {
        $dir = dirname($pidFile);
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new RuntimeException("Cannot create directory for PID file: $dir");
        }

        $fp = @fopen($pidFile, 'c+');
        if (!$fp) {
            throw new RuntimeException("Cannot open PID file: $pidFile");
        }

        // Try to get exclusive non-blocking lock
        if (!flock($fp, LOCK_EX | LOCK_NB)) {
            // Another instance (or stale holder) has the lock — read its PID
            rewind($fp);
            $oldPid = (int)trim(stream_get_contents($fp));

            if ($oldPid > 1 && posix_kill($oldPid, 0)) {
                // Old PID is alive — kill it
                self::killProcess($oldPid, $forceKill);
            } else {
                // Old PID is dead/invalid, but lock still held (likely child processes)
                echo "Old PID $oldPid is dead, but lock held by stale processes. Finding holders...\n";

                // Use fuser to get all PIDs holding this file open
                exec('fuser ' . escapeshellarg($pidFile) . ' 2>/dev/null', $output);
                $holders = [];
                foreach ($output as $line) {
                    $pids = preg_split('/\s+/', trim($line));
                    foreach ($pids as $p) {
                        $hp = (int)$p;
                        if ($hp > 1) $holders[] = $hp;
                    }
                }
                $holders = array_unique($holders);

                // Kill all holders (except ourselves, though we don't hold it yet)
                $myPid = getmypid();
                foreach ($holders as $hPid) {
                    if ($hPid === $myPid || !posix_kill($hPid, 0)) continue;

                    echo "Killing stale holder PID $hPid ...\n";
                    posix_kill($hPid, SIGTERM);

                    // Wait up to 5 seconds for graceful exit
                    $waitStart = microtime(true);
                    while (microtime(true) - $waitStart < 5 && posix_kill($hPid, 0)) {
                        usleep(200000); // 0.2s
                    }

                    if (posix_kill($hPid, 0) && $forceKill) {
                        echo "Force killing stale holder PID $hPid ...\n";
                        posix_kill($hPid, SIGKILL);
                    }
                }

                // Short wait for kernel to release the lock
                usleep(500000); // 0.5 sec
            }

            // Now try to grab the lock again
            if (!flock($fp, LOCK_EX | LOCK_NB)) {
                fclose($fp);
                throw new RuntimeException("Lock still held after kill attempts (check for unrelated processes holding $pidFile)");
            }
        }

        // We have the lock — truncate and write our PID
        ftruncate($fp, 0);
        fwrite($fp, getmypid() . "\n");
        fflush($fp);

        // Register cleanup on shutdown
        register_shutdown_function(function () use ($pidFile, $fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            if (file_exists($pidFile)) @unlink($pidFile); // optional: remove file on clean exit
        });
    }

    /**
     * Helper: kills a process gracefully, then forcefully if needed
     */
    private static function killProcess(int $pid, bool $forceKill): void
    {
        if ($pid <= 1 || !posix_kill($pid, 0)) {
            // PID invalid or already dead
            return;
        }

        echo "Killing instance PID $pid ...\n";
        posix_kill($pid, SIGTERM);

        // Wait up to 5 seconds for graceful exit
        $waitStart = microtime(true);
        while (microtime(true) - $waitStart < 5 && posix_kill($pid, 0)) {
            usleep(200000); // 0.2s
        }

        if (posix_kill($pid, 0) && $forceKill) {
            echo "Force killing PID $pid ...\n";
            posix_kill($pid, SIGKILL);
        }
    }

    /**
     * Optional: just check if lock is held (without taking it)
     */
    public static function isRunning(string $pidFile): bool
    {
        if (!file_exists($pidFile)) return false;

        $fp = @fopen($pidFile, 'r');
        if (!$fp) return true; // assume running if can't read

        if (flock($fp, LOCK_EX | LOCK_NB)) {
            flock($fp, LOCK_UN);
            fclose($fp);
            return false;
        }

        $pid = (int)trim(fgets($fp));
        fclose($fp);

        return posix_kill($pid, 0); // true if process exists
    }
}