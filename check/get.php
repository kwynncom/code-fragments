<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/mystery_2024_0920_1/params.php');
require_once('getDOM.php');
require_once('getGeneric.php');
require_once('dao.php');

class mysckCl implements mysckpopa_parse {

    const shouldDoIt = true;
    
    public  readonly string $pageActionRawTxt;
    public  readonly string $pageActionTodayTxt;
    private readonly string $replyKey;
    public  readonly bool   $needToAct;
    private readonly object $dom;
    public  readonly string $rawht;

    public function __construct() {
	$dom = $this->getDOM();
	$this->do20($dom); unset($dom);
	$this->todayTest();
	$this->do40();
	$this->reportCli();
    }

    private function reportCli() {
	if (!iscli()) return;
	echo('Action Needed = ' . ($this->needToAct ? 'Yes' : 'no') . "\n");
    }

    private function do40() : bool {

	if   (   substr($this->pageActionTodayTxt,  0, 7) === 'You are' && $this->replyKey === 'Y') $this->needToAct = true; 
	else if (substr($this->pageActionTodayTxt, 12, 6) === 'is not'  && $this->replyKey === 'N') $this->needToAct = false;

	
	kwas(      isset  ($this->needToAct) 
	        && is_bool($this->needToAct), 'bad YN-field value 350418 badhtv');
	return	           $this->needToAct; // redundant check
    }
    

    private function todayTest() {
	$test = self::replyStrings[$this->replyKey] . date('F d') . '.';
	kwas($this->pageActionRawTxt === $test, 'bad result 270335 - date is not today' ); unset($test);
	$this->pageActionTodayTxt = $this->pageActionRawTxt;
    }


    private function do20(object $dom) {
	$f = $dom->getElementById('en-result'); unset($dom);
	$l = $f->getElementsByTagName('label')->item(0); unset($f);
	kwas($l->getAttribute('for') === 'reply', 'bad match ele 230321');
	$this->pageActionRawTxt = $l->nodeValue; unset($l);
	foreach(self::replyStrings as $tfv => $vv) {
	    $ss = substr($this->pageActionRawTxt, 0, strlen($vv));
	    if ($ss === $vv) {
		$this->replyKey = $tfv;
		return;
	    }
	}

	kwas(false, 'reply does not match 370327');
	return;
    }


    
    private function getDOM() : object {

	$posta  = mysckpopa_get::post;

	if (self::shouldDoIt) {
	    $source = mysckpopa_get::url;
	}
	else { 
	    $rand = random_int(0, count(self::tf) - 1);
	    $source = self::tf[$rand];
	}

	$reso = genericGETCl::get($source, $posta);
	actCheckBegin2024DAOCl::put($reso);
	$this->rawht = $reso->body;
	return $reso->dom;

	
    }

    private function putRecord(string $res) : string {

	static $i = 0;

	$f = date('Y-m-d_Hi_s_') . '' . ++$i . '_' . (PHP_SAPI === 'cli' ? 'cli' : 'www') . '_' . (self::shouldDoIt ? 'live' : 'test') . '.html';
	$p = self::resStoragePath . $f;
	$fpr = file_put_contents($p, $res);
	kwas($fpr === strlen($res), 'bad file put 770356');
	return $res;
    }

}

if (didCLICallMe(__FILE__)) new mysckCl();