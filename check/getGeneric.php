<?php

class genericGETCl {


    const maxRLen = 4500;
    const minRLen = 3000;

    public readonly float  $UusBeforeGET;
    public readonly float  $UusAfterGET ;
    public readonly string $sourceHighLevel;
    public readonly string $body;
    public	    array  $validations;

    public static function get(string $source, array $posta = []) : object {
	$o = new self();
	$o->getI($source, $posta);
	return $o;
    }

    private function getI(string $source, array $posta) : string {
	
	$this->UusBeforeGET = microtime(true);
	
	if ($source[0] !== '/') {
	    $this->sourceHighLevel = 'curl';
	    $this->body = $this->curlGET($url, $posta);
	} else {
	    $this->sourceHighLevel = 'file_local_test';
	    $this->body = file_get_contents($source);
	}


	$this->UusAfterGET  = microtime(true);

	return $this->validrord($this->body);

    }

    private function validrord($resin): string {
	kwas($resin && is_string($resin), 'response fail 1100302');
	$l = strlen($resin);
	kwas($l >= self::minRLen && $l <= self::maxRLen, 'response fail 2140303');
	$this->validations['filesize'] = true;
	return $resin;
    }


    private static function curlGET(string $url, array $posta) : string {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $posta);

	if (iscli()) echo('Running CURL...' . "\n");

	$response = curl_exec($ch);
	curl_close($ch);
	return $reponse;

    }

}