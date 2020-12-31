<?php

require_once('/opt/kwynn/kwutils.php');

class AWSCryptoV { 
    
     
    const upfx    = 'http://169.254.169.254/latest/dynamic/instance-identity/';
    const pubf    = 'AWSPubKey_2020_01_1.txt';
    const pubsha256 = 'c02cf542248f66abbea9df49591344d161510d63337b0fd782c4ecd5e959f07a';
    const publen    = 1074;
    const pubp  = 'https://kwynn.com/t/9/12/sync/services/';
    const tmp     = '/tmp/iid_kwns202/';
    const iiddocs    = ['document', 'rsa2048', 'signature', 'pkcs7'];
    
    private function init() {
	$this->alls = '';
    }
    
    public function getPut($inp, $outp, $fn) {
	$din = file_get_contents($inp); 
	$this->alls .= $din;
	if ($fn === self::pubf) {
	    $hash = hash('sha256', $din);
	    kwas($hash === self::pubsha256, 'pub AWS key hash fail'); unset($hash);
	    $l = strlen($din);
	    kwas($l === self::publen, 'pub AWS key size fail'); unset($l);
	} else if ($fn === 'document') $this->jsoniddoc = trim($din);
	
	file_put_contents($outp, $din);
    }

    private function sha() {
	echo('all ID files: ' . hash('sha256', $this->alls) . "\n");
    }
    
    private function crypto() {
    
    if (!file_exists(self::tmp)) mkdir(self::tmp);
    chmod(self::tmp, 0700);

    $this->getPut(self::pubp . self::pubf, self::tmp . self::pubf, self::pubf);
    foreach(self::iiddocs as $f) $this->getPut(self::upfx . $f, self::tmp . $f, $f);
    $this->sha();
    
    $pkfn =  'pkcs7';
    $pks  = "-----BEGIN PKCS7-----\n";
    $pkp = self::tmp . $pkfn;
    $pks .= file_get_contents($pkp);
    $pks .=  "\n-----END PKCS7-----\n";
    $pkmp = $pkp . '_mod';
    file_put_contents($pkmp, $pks); unset($pks, $pkfn);
    
    $c  = 'openssl smime -verify -in ';
    $c .= self::tmp . 'pkcs7_mod ';
    $c .= '-inform PEM -content ';
    $c .= self::tmp . 'document ';
    $c .= '-certfile ';
    $c .= self::tmp . self::pubf;
    $c .= ' -noverify ';

    echo($c . "\n");
    
    exit(0);
    
    
    
    // openssl smime -verify -in $PKCS7 -inform PEM -content $DOCUMENT -certfile AWSpubkey -noverify > /dev/null
    
    $c  = '';
    $c .= 'openssl smime -verify -in ';
    $c .= $td . $fs[1][1];
    $c .= ' -inform PEM -content ';
    $c .= $td . self::iddocnm;
    $c .= ' -certfile ';
    $c .= self::awspkf;
    $c .= ' -noverify ';
    $c .= ' 2>&1 1> /dev/null';
     
    $re = '/^(Verification successful)\s*$/';    
    $fr['cmd'] = $c;
    $fr['regex'] = $re;   
    $fr['result'] = '';
    
    $fr2 = [];
    
    if (isset($docr['publicRes'])) $fr2['fieldTests'] = $docr['publicRes'];
    $fr2['cryptoCmd'] = $fr;
    
    if (!isAWS())     return ['iddoc' => $fr2];

    $sres = shell_exec($c);
 
    preg_match($re, $sres, $matches);
    kwas(isset($matches[1]), 'AWS crypto match failed');

    $fr2['cryptoCmd']['result'] = $matches[1];
        
    return ['iddoc' => $fr2];
}
    public function __construct() {
	$this->init();
	$this->crypto();
    }

}

if (didCLICallMe(__FILE__)) new AWSCryptoV();
