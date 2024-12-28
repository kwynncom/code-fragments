<?php

// INSTALL:
// composer require php-webdriver/webdriver
// aka Facebook WebDriver
namespace Facebook\WebDriver; // needs to be first line

// For this example, I use the following mainly because it invokes composer / vendor autoload
require_once('/opt/kwynn/kwutils.php'); // from https://github.com/kwynncom/kwynn-php-general-utils


use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;


// I *think* geckodriver comes with the Firefox snap
// If you're already installing FF from snap, you should probably use the snap gecko version for reasons I don't remember.


// Redirect stderr (2) because the output is probably just annoying
// & to run gecko in the background and then the echo $! prints the PID of gecko
$processResource = popen('/snap/bin/firefox.geckodriver 2> /dev/null & echo $!', 'r');
$geckoDriverPid = trim(fgets($processResource));
fgets($processResource); // if I read the 'listening' line, then I don't have to sleep.  Otherwise I have to sleep for ~3 seconds or it crashes.

$urlGeckoServer = 'http://localhost:4444'; // gecko's default port
$driver = RemoteWebDriver::create($urlGeckoServer, DesiredCapabilities::firefox());


$webPageURLlToConfirm = 'https://kwynn.com'; // any live, public site to confirm it's working
$driver->get($webPageURLlToConfirm);

if (true) sleep(2); // the sleep is ONLY for the example so you can see / confirm the site.  You don't need to sleep if you're running a scraper.

$driver->quit();

pclose($processResource);

exec('kill ' .  $geckoDriverPid); // This took a lot of work.  I could not find any other way to kill it.
// If you don't kill it, the listener will stay running.  If that happens, the next invokating will die because 4444 is in use

// I use the true simply to show that it's an unnecessary option, like the sleep above
if (true) echo('Kwynn example rc 2, git 1: 2024/12/28 08:25 America/New_York' . "\n");