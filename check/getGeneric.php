<?php

class genericGETCl {


    const maxRLen = 4500;
    const minRLen = 3000;

    const minCopyrightYear = 2024;

    public readonly float  $UusBeforeGET;
    public readonly float  $UusAfterGET ;
    public readonly string $sourceHighLevel;
    public readonly string $body;
    public	    array  $validations;
    public readonly object $dom;

    public static function get(string $source, string $copyrightOwner, array $posta = []) : object {
	$o = new self();
	$o->getI($source, $copyrightOwner, $posta);
	return $o;
    }

    private function getI(string $source, string $cro, array $posta) {
	
	$this->UusBeforeGET = microtime(true);
	
	if ($source[0] !== '/') {
	    $this->sourceHighLevel = 'curl';
	    $this->body = $this->curlGET($url, $posta);
	} else {
	    $this->sourceHighLevel = 'file_local_test';
	    $this->body = file_get_contents($source);
	}


	$this->UusAfterGET  = microtime(true);

	$this->validrord($this->body);

	$this->checkCopyrightOrDie($this->body, $cro);

    }



    private function checkCopyrightOrDie(string $t, string $cro) : bool {

	$this->dom = getDOM($t); unset($t);
	$p = $this->dom->getElementById('en-copy');
	$c10 = mb_substr($p->nodeValue, 0, 1);
	kwas($c10 === '©', 'bad value reply 480431');
	$c20 = intval(mb_substr($p->nodeValue, 2, 4));
	kwas(is_integer($c20) && $c20 >= self::minCopyrightYear, 'bad value 510438');
	$this->validations['copyright_year'] = true;

	$cro = trim($cro);
	$lcro = strlen($cro);
	kwas($lcro > 0, 'bad owner len bownl');
	$c30 = mb_substr($p->nodeValue, 7, $lcro);
	kwas($c30 === $cro, 'bad value 530441');
	$this->validations['copyright_owner'] = true;

	return true;

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