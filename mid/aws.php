<?php

require_once('/opt/kwynn/kwutils.php');

class isAWSCmds { 
    
     
    const upfx    = 'http://169.254.169.254/latest/dynamic/instance-identity/';
    const pubf    = 'AWSPubKey_2020_01_1.txt';
    const pubp  = 'https://kwynn.com/t/9/12/sync/services/';
    const tmp     = '/tmp/iid_kwns202/';
    const docs3    = ['document', 'rsa2048', 'signature'];
    
    public static function getPut($inp, $outp) {
	file_put_contents($outp, file_get_contents($inp));
    }

public static function crypto() {
    
    if (!file_exists(self::tmp)) mkdir(self::tmp);
    chmod(self::tmp, 0700);
    
    $pkfn =  'pkcs7';
    
    $pks  = "-----BEGIN PKCS7-----\n";
    $pks .= file_get_contents(self::upfx . $pkfn);
    $pks .=  "\n-----END PKCS7-----\n";
    file_put_contents(self::tmp . $pkfn, $pks); unset($pks, $pkfn);
    
    self::getPut(self::pubp . self::pubf, self::tmp . self::pubf);
    foreach(self::docs3 as $f) self::getPut(self::upfx . $f, self::tmp . $f);
    
    $c  = 'openssl smime -verify -in ';
    $c .= self::tmp . 'pkcs7 ';
    $c .= '-inform PEM -content ';
    $c .= self::tmp . 'document ';
    $c .= '-certfile ';
    $c .= self::tmp . self::pubf;
    $c .= ' -noverify ';
    $c .= ' 1> /dev/null';

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
}

if (didCLICallMe(__FILE__)) isAWSCmds::crypto();
