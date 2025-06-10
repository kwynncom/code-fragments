<?php

namespace Facebook\WebDriver;
use Facebook\WebDriver\Exception\WebDriverException;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Firefox\FirefoxProfile;

require_once('/opt/kwynn/kwutils.php');

class KwSeleniumBaseCl 
{

// probably in something vaguely like /home/user/snap/firefox/common/.mozilla/firefox/1234a2bc3.default
// contains places.sqlite, cookies.sqlite, and many others
    const firefoxProfilePath   = '/var/kwynn/devFirefoxProfile';

    // log file to check Selenium startup
    const SelLogPrefix = '/tmp/sellog-kw-';

    public  readonly object	    $driver;
    public  readonly int	    $selpid;  

    public static function test() {
	if (!didCLICallMe(__FILE__)) return;
	$o = new KwSeleniumBaseCl();
	$o->startAll();
	$o->driver->get('https://kwynn.com/t/25/05/test.php');
	echo($o->driver->getPageSource());
	echo("\n" . 'This script will sleep / wait for a few moments....' . "\n");
	sleep(3);
	echo('Done.' . "\n");
    }
 
    public function startDriver() {

	$capabilities = DesiredCapabilities::firefox();

	// mine is a sym link; either way, realpath probably can't do any harm.  As I remember, I found the hard way that a 
	// sym link didn't work	
	$firefoxOptions = ['args' => ['--profile', realpath(self::firefoxProfilePath) ]	];
	
	$capabilities->setCapability('moz:firefoxOptions', $firefoxOptions);
	$capabilities->setCapability('marionette', true);

	$this->driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    public function __destruct() {
	try {
	    if (isset($this->driver)) $this->driver->quit(); // must be done before killing the server just below
	    if (isset($this->selpid)) {
		$pkr =    posix_kill($this->selpid, 0);
		if ($pkr) posix_kill($this->selpid, SIGTERM);
	    }
	} catch(\Exception $ex) { }
    }

    public function startAll() {
	$this->startListenerIfOff();
	$this->startDriver();
    }

    private function checkLStart(string $f)  {
	for ($i=0; $i < 60; $i++) {
	    usleep(100000);
	    $t = file_get_contents($f);
	    if (!$t) continue;
	    if (strpos($t, 'ERROR') !== false) {
		if (PHP_SAPI === 'cli') echo($t);
		kwas(false, 'sel listener log file error (error #0332109)'); 
	    }

	    if (strpos($t, 'INFO [Standalone.execute] - Started Selenium Standalone' )) {
		return true;
	    }
	}
	
	kwas(false, 'sel timeout error #0333115');
    }

    private static function getLogFile() : string {
	$f  = '';
	$f.= self::SelLogPrefix;
	$f .= '-' . (PHP_SAPI === 'cli' ? 'cli' : 'www') . '.txt';
	file_put_contents($f, '');
	kwas(chmod($f, 0600), 'cannot change permission of ' . $f . ' err #022196');
	return $f;
    }


    private function startListener() {
	$f = $this->getLogFile();
	$c  = '';
	$c .= 'java -jar /usr/local/bin/selenium-server.jar standalone 2>&1 > ';
	$c .= $f . ' ';
	$c .= ' & echo $!';

	$handle = popen($c, 'r'); unset($c);
	$this->checkLStart($f);
	$this->selpid = intval(trim(fgets($handle)));
	pclose($handle);
	kwas($this->selpid >= 1, 'bad Selenium server pid (error message 252236)');
	kwas($this->isGoodStartListener($this->selpid), 'bad startListener Sel attempt 231541');
    }

    private function getUrlContents($url) : string { // partially from Grok 3.0 2025/06/10 06:10 AM EDT
	try {
	    $ch = curl_init($url); kwas($ch, 'curl_init error');
	    curl_setopt_array($ch, [
		CURLOPT_RETURNTRANSFER => true, // Return response as string
		CURLOPT_FOLLOWLOCATION => true, // Follow redirects
		CURLOPT_TIMEOUT => 5,
		CURLOPT_FAILONERROR => false, ]);

	    $response = curl_exec($ch);
	    kwas($response && !curl_error($ch), 'bad response or curl error');
	    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    kwas($httpCode === 200, "http response code $httpCode (not 200)");
	    return $response;
	} catch(\Exception $ex114) { return KW0S; } finally { curl_close($ch);	}

	return KW0S;
    }

    private function isGoodStartListener() : bool {
	try {
	    $sres = $this->getUrlContents('http://localhost:4444/status');
	    if (!$sres) return false;
	    $a = json_decode($sres, true); unset($sres);
	    $ready = kwifs($a, 'value', 'ready', ['kwiff' => null]);
	    kwas(is_bool($ready), 'Selenium listener / server bad status 023131');
	    if (!$ready) kwas(kwifs($a, 'value', 'message') === 'Session already started', 'bad Sel li value 030632');

	    return true;

	} catch(\Exception $ex) { 
	    return false;
	}

	return false;
    }

    private function startListenerIfOff() {

	for($i=0; $i < 2; $i++) { // check if, then try to start, then ok or fail, so up to 2 tries
	    try {
		if  ($this->isGoodStartListener()) return;
		$this->startListener();
	    } catch(\Exception $ex145) { }
  	}

	kwas(false, 'From "if off" cannot start Selenium server - 230876');
    }
}

KwSeleniumBaseCl::test();
