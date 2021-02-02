<?php

class ntp_output {
    public function __construct($isbatch) {
	$this->tit = 0;
	$this->barr = [];
	$this->isbatch = $isbatch;
	$this->sdo = new stddev();	
    }
    
    public function outFinal() {
	if (!$this->isbatch) return;
	$this->bynetd();
	// var_dump($this->sdo->get());
    }
    
    public function badout() {
	if (!isset($this->badrd)) return;
	foreach($this->badrd as $s) echo($s);
    }
    
    public function out($ddin) {

	$nms = $this->outnet($ddin['all']);
	
	if (isset($ddin['off']) || $nms < 0.6) {
	    $v =  $ddin['off'];
	    
	    if ($v > M_MILLION) {
		$odf = '%+06.2f';
		$nf  = '%02d';
	    } else {
		$odf = '%+08.5f';
		$nf  = '%04.2f';
	    }

	    
	    $v *= 1000;
	    $this->sdo->put($v);
	    $vd = sprintf($odf, $v);

	    if ($nms > 99.92) return;
	    $nmsd = sprintf($nf, $nms);
	    $s = $vd . ' ' . $nmsd;
	    
	    $isx = $ddin['si']['pool'] !== 'kwynn';
	    if ($isx) $s .= ' ' . $ddin['si']['pool'] . ' ' . $ddin['si']['server'];
	    // $s = ($vd . ' ' . $nmsd /* . ' ' . $ddin['srv']  */ .    "\n");
	    $s .= "\n";
	    if ($isx) echo($s);
	    $this->barr[$this->tit++]['dis'] = $s;
	    kwynn();
	} else {
	    $s = ($ddin['status'] . ' ' . $ddin['server'] . "\n");
	    $this->badrd[] = $s;
	}
    }
    
    private function outnet($d) {
	$rawe = $d['local']['e'];
	$rawb = $d['local']['b'];
	$ns = self::nanoatoi($rawe) - self::nanoatoi($rawb);
	$msf = $ns / M_MILLION;
	$this->barr[$this->tit]['netdf' ] = $msf;
	$this->barr[$this->tit]['netdns'] = $ns;
	
	return $msf;
	// $ms = intval(round($msf));
	// return $ms;
    }
    
    private function bynetd() {
	usort($this->barr, ['self', 'sort']);
	// echo('***********' . "\n");
	foreach($this->barr as $r) echo($r['dis']);
    }
    
    private static function sort($a, $b) {
	return $b['netdns'] - $a['netdns'];
    }
    
    public static function nanoatoi($ain) {
	return $ain['s'] * M_BILLION + $ain['ns'];

    }
}