<?php

declare(strict_types=1);

class adbBattParseCl {

    public readonly array  $a;
    public readonly string $chargingBy;
    public readonly int	   $level;
    public readonly float  $V;
    public readonly float  $F;
    public readonly float  $C;
    public readonly int	   $uAh;

    public static function get(array $a) {
	$o = new self($a);
	return $o;
    }

    private function __construct(array $a) {
	$this->do10($a);
    }

    // max possible ch A is 13
    // max possible ch V is 25
    // both are given in u, so uA and uV
    // check rest of settings

    private function do10(array $a) {

	kwas(count($a) === 15, 'bad adb batt lines / count - err # 054412');
	$this->a = $a;
	$this->setChargingBy();
	$this->setLevel();
	$this->setVoltage();
	$this->setTemp();
	$this->setChargeCounter();
	return;
    }

    private function setChargeCounter() {
	$raw = $this->a['Charge counter']; kwas(is_numeric($raw), 'non-num Charge counter - err # 130632');
	$iv  = intval($raw);
	kwas($iv >= 0 && $iv <= 20000000, 'unlikely Charge counter value - err # 131034');
	$this->uAh = $iv;


    }

    private function setTemp() {
	$raw = $this->a['temperature']; kwas(is_numeric($raw), 'non-num temp - err # 125728');
	$C   = ($raw / 10);
	$F   = ($C * 9/5) + 32;
	kwas($F > -15 && $F < 180, 'unhealthy, dangerous, or impossible temp - err # 130031');
	$this->F = $F;
	$this->C = $C;

    }

    private function setVoltage() {
	$raw = $this->a['voltage']; kwas(is_numeric($raw), 'non-num voltage - err # 125425');
	$V = $raw / 1000;
	kwas($V > 2.9 && $V < 5, 'voltage is either unhealthly or bad reading - err #125527');
	$this->V = $V;

    }

    private function setLevel() {
	$raw = $this->a['level']; kwas(is_numeric($raw), 'non-num level or batt % - err # 124723');
	$iv  = intval($raw); unset($raw);
	kwas($iv >= 0 && $iv <= 100, 'bad level int value - err #124725');
	$this->level = $iv;
	return;
    }

    private function setChargingBy() {

	$ch = '';

	foreach(['AC', 'USB', 'Wireless'] as $source) {
	    $key = $source . ' ' . 'powered';
	    kwas(isset($this->a[$key]), 'key ' . $key . ' not found - err # 240550');
	    $vs =       $this->a[$key];
	    $v  = $vs === 'true' ? true : false;
	    if ($v === false) kwas($vs === 'false', 'bad true false charge value - err # 300558');
	    if (!$v) continue;
	    if ($ch) kwas(false, 'more than one charging method - err # 055432');
	    $ch = $source;
	    continue;
	}

	$this->chargingBy = $ch;
    }
    
}

