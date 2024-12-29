<?php // confirmed version 2024/12/28 23:04 - commented first two object vars

// This is the objectified form of:
// https://github.com/kwynncom/code-fragments/blob/319e4f40624119e09da84c645ea321354a93154f/seleniumExample.php

namespace Facebook\WebDriver;

require_once('/opt/kwynn/kwutils.php');

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;


class SeleniumBaseCl {

    public  readonly object $driver; // Firefox Gecko driver
    private readonly mixed  $lihan;  // listener handler
    private readonly int    $lipid;
    private readonly string $toBeScrapedURL;

    public function __construct(string $toBeScrapedURL = 'https://kwynn.com') {
	$this->toBeScrapedURL = $toBeScrapedURL; unset($toBeScrapedURL);
	$this->startListener();
	$this->startDriver();
    }

    public function __destruct() { // destructors probably have to be public if the constructor is, or perhaps always
	$this->driver->quit();
	pclose($this->lihan);
	exec('kill ' .  $this->lipid);
    }

    private function startListener() {

	$this->lihan = popen('/snap/bin/firefox.geckodriver 2>&1 & echo $!', 'r');
	$this->lipid = intval(trim(fgets($this->lihan)));
	kwas($this->lipid >= 1, 'bad gecko pid 252236');
	fgets($this->lihan);
    }

    private function startDriver() {
	$this->driver = RemoteWebDriver::create('http://localhost:4444', DesiredCapabilities::firefox());
	$this->driver->get($this->toBeScrapedURL);
    }

}

if (didCLICallMe(__FILE__)) {
    $o = new SeleniumBaseCl();
    sleep(2); // only needed for an example
    unset($o);
}
