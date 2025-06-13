<?php

class acctTemplate10Cl {

    private string $ht;
    private array  $ta = [];
    private readonly array $a;
    private readonly array $calcs;
    private	     string $table = '';

    private function renderCalcs() {
	$t = '';
	ob_start();
	// __DIR__ . '/' .
	require_once( 'template20.php');
	$t .= ob_get_clean();

	$this->ht .= $t;
    }

    public function __construct() {
	$this->head();
    }

    private function head() {
	$this->ht  = '';
	$this->ht .= file_get_contents(__DIR__ . '/head.html');

    }

    public function putLine(array $r) {
	$this->ta[] = $r;
    }

    private function doMid() {
	$this->a = array_reverse($this->ta); unset($this->ta);

	$a = array_slice($this->a, 0, 5);

	foreach($a as $r) {
	    $this->doLine($r);
	}
    }

    private function doLine(array $r) {

	static $i = 0;

	$t  = '';

	$t .= $this->renderCalcs();

	if ($i++ === 0) $t .= '<table>' . "\n";

	$t .= '<tr>';
	
	$d = date('F d, Y', strtotime($r['huDatePosted']));
	$amt = number_format($r['amount'], 2);
	$bal = number_format($r['bal'], 2);
	
	$t .= trim(<<<HTTD38
	    <td>$d</td><td>$r[otherAcctName]</td><td>$amt</td><td>$bal</td>\n
HTTD38);

	$t .= '</tr>' . "\n";	

	$this->table .= $t;

    }

    private function done() {
	$this->doMid();
	$this->table .= '</table>' . "\n";

	$this->ht .= $this->renderCalcs();
	$this->ht .= $this->table; unset($this->table);
	$this->ht .= "</body>\n</html>\n";
    }

    public  function getHTML(array $calcs) {
	$this->calcs = $calcs;
	$this->done();
	return $this->ht;
    }
}
