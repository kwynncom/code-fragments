<?php

class acctTemplate10Cl {

    private string $ht;
    private array  $ta = [];
    private readonly array $a;

    public function __construct() {
	$this->head();
    }

    private function head() {
	$this->ht  = '';
	$this->ht .= file_get_contents(__DIR__ . '/html/head.html');
	$this->ht .= '<table>' . "\n";

    }

    public function putLine(array $r) {
	$this->ta[] = $r;
    }

    private function doMid() {
	$this->a = array_reverse($this->ta); unset($this->ta);
	foreach($this->a as $r) {
	    $this->doLine($r);
	    
	}
    }

    private function doLine(array $r) {
	$t  = '';
	$t .= '<tr>';
	
	$d = date('F d, Y', strtotime($r['huDatePosted']));
	$amt = number_format($r['amount'], 2);
	$bal = number_format($r['bal'], 2);
	
	$t .= trim(<<<HTTD38
	    <td>$d</td><td>$r[otherAcctName]</td><td>$amt</td><td>$bal</td>\n
HTTD38);

	$t .= '</tr>' . "\n";	

	$this->ht .= $t;

    }

    private function done() {
	$this->doMid();
	$this->ht .= "</table>\n</body>\n</html>\n";
    }

    public  function getHTML() {
	$this->done();
	return $this->ht;
    }
}
