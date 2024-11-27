<?php

declare(strict_types=1);

require_once('/opt/kwynn/kwutils.php');
require_once('/var/kwynn/mystery_2024_0920_1/params.php');

class mysckCl implements mysckpopa {

    const shouldDoIt = false;
    const maxRLen = 4500;
    const minRLen = 3000;
    const minCopyrightYear = 2024;
    
    private readonly string $pageActionRawTxt;
    private readonly string $pageActionTodayTxt;
    private readonly string $replyKey;
    private readonly bool   $needToAct;

    public function __construct() {
	$res = $this->get();
	$dom = $this->getDOM($res); unset($res);
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

    private function checkCopyrightOrDie(object $dom) : bool {
	$p = $dom->getElementById('en-copy');
	$c10 = mb_substr($p->nodeValue, 0, 1);
	kwas($c10 === '©', 'bad value reply 480431');
	$c20 = intval(mb_substr($p->nodeValue, 2, 4));
	kwas(is_integer($c20) && $c20 >= self::minCopyrightYear, 'bad value 510438');
	$c30 = mb_substr($p->nodeValue, 7, strlen(self::copyrightOwner));
	kwas($c30 === self::copyrightOwner, 'bad value 530441');
	return true;

    }

    private function do20(object $dom) {
	kwas($this->checkCopyrightOrDie($dom) === true, 'bad value 590441');
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

    private function getDOM(string $t) : object {
	$o = new DOMDocument();
	libxml_use_internal_errors(true);
	$o->loadHTML($t); unset($t);
	libxml_clear_errors();	
	return $o;

    }

    private function validrord($resin) : string {
	kwas($resin && is_string($resin), 'response fail 1100302');
	$l = strlen($resin);
	kwas($l >= self::minRLen && $l <= self::maxRLen, 'response fail 2140303');
	return $resin;
    }
    
    private function get() {
	if (self::shouldDoIt) return $this->getActual();
	$rand = random_int(0, count(self::tf) - 1);
	$tr = file_get_contents(self::tf[$rand]);
	return $this->validrord($tr);
	
    }

    private function putRecord(string $res) : string {

	static $i = 0;

	$f = date('Y-m-d_Hi_s_') . '' . ++$i . '_' . (PHP_SAPI === 'cli' ? 'cli' : 'www') . '_' . (self::shouldDoIt ? 'live' : 'test') . '.html';
	$p = self::resStoragePath . $f;
	$fpr = file_put_contents($p, $res);
	kwas($fpr === strlen($res), 'bad file put 770356');
	return $res;
    }

    private function getActual() : string {


	$ch = curl_init(mysckpopa::url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, mysckpopa::post);


	if (self::shouldDoIt) {
	    $response = curl_exec($ch);
	    curl_close($ch);
	    $vres = $this->validrord($response);
	    return $this->putRecord($vres);
	}

	return 'check is turned off';

    }
}

if (didCLICallMe(__FILE__)) new mysckCl();