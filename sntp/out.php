<?php

class ntp_output {
    public function __construct($isbatch, $dets) {
	$this->tit = 0;
	$this->soarr = [];
	$this->isbatch = $isbatch;
	$this->sdo = new stddev();	
	$this->dets = $dets;
    }
    
    public function outFinal() {
	if (!$this->isbatch) return;
	if (!$this->dets) $this->bynetd();
	else $this->dallout();
	// var_dump($this->sdo->get());
    }
    
    public function badout() {
	if (!isset($this->badrd)) return;
	foreach($this->badrd as $s) echo($s);
    }
    
    public function out($ddin) {

	if (!isset($ddin['all'])) return;
	
	if ($this->dets) {
	    $this->darr[] = $ddin;
	    return; // $this->dout($ddin);
	}
	
	$nms = $this->outnet($ddin['all']);
	
	if (isset($ddin['off'])) {
	    $v =  $ddin['off'];
	    
	    if ($v > M_MILLION || $nms > 0.6) {
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
	    $this->soarr[$this->tit++]['dis'] = $s;
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
	$this->soarr[$this->tit]['netdf' ] = $msf;
	$this->soarr[$this->tit]['netdns'] = $ns;
	
	return $msf;
	// $ms = intval(round($msf));
	// return $ms;
    }
    
    private function bynetd() {
	usort($this->soarr, ['self', 'sortsimp']);
	// echo('***********' . "\n");
	foreach($this->soarr as $r) echo($r['dis']);
    }
    
    private static function sortsimp($a, $b) {
	return $b['netdns'] - $a['netdns'];
    }
    
    public static function nanoatoi($ain) {
	return $ain['s'] * M_BILLION + $ain['ns'];

    }
    
    private static function sortdets($a, $b) {
	
	$ad = $a['all']['based']['lr'] - $a['all']['based']['ls'];
	$bd = $b['all']['based']['lr'] - $b['all']['based']['ls'];
	$d  = intval(($ad - $bd) * 100000);
	
	return $d;
	
    }
    
    private function dallout() {
	usort($this->darr, ['self', 'sortdets']);
	foreach($this->darr as $r) $this->dout($r);
	
    }
    
    
    
    private function dout($ddin) {
	
	$d = $ddin['all'];
	
	$b = $d['based'];
	
	$s = '';
	$si = $b['rs'] - $b['rr'];
	$sid = round($si * M_MILLION);
	$s .= $sid;
	$s .= ' serv internal time (us)';
	$s .= "\n";
	
	$n = $b['lr'] - $b['ls'];
	$nd = round($n * 1000);
	$s .= $nd . ' net' . "\n";
	
	$av = ($b['rs'] + $b['rr']) / 2;
	$b['av'] = $av;
	
	$out = $b['rr'] - $b['ls'];
	$outd = round($out * 1000);
	$s .= $outd . ' out ' . "\n";
	
	$in = $b['lr'] - $b['rs'];
	$ind = round($in * 1000);
	$s .= $ind . ' in ' . "\n";
	
	$fs = ['ls', 'av', 'rr'];
	
	foreach($fs as $f) $s .= $b[$f] . "\n";
	
	echo($s);
exit(0);	
	
	return;
    }
}
