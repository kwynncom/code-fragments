<?php // 2022/07/28 01:21

// About to separate this file from HTML stuff
// Hopefully a lesson in objectification--going from a function to a class / object.

require_once('/opt/kwynn/kwutils.php');

if ((!ispkwd()) || (time() > strtotime('2022-07-28 03:59'))) die('expired');


class sortSiteByTime {

public static function getPaths() {
	new self();
}
	
private function __construct() {
	
	$this->set10();
	$this->p20();
	$this->doHT();
}

private function set10() {
	$this->droot = $root = $_SERVER['DOCUMENT_ROOT'];
	$c = 'find ' . $root . '/ ' . ' -type f -printf "%T+\t%p\n" | sort -r ';
	$res = shell_exec($c); unset($c);
	$this->therawa = explode("\n", $res); unset($res);
}

private function doHT() {
	$ht = '';
	$ht .= file_get_contents(__DIR__ . '/top.html');
	foreach($this->theps as $p) {
		$ht .= '<tr>';
		$ht .= '<td>';
		$ht .= '<a href="' . $p . '">' . $p . '</a>';
		$ht .= '</td>';
		$ht .= '</tr>' . "\n";		
	}
	
	$ht .= "</table>\n</body>\n</html>\n";
	echo($ht);
	
}

private function p20() {

	$a = $this->therawa;

	$a20 = [];
	foreach($a as $r) {
		if (preg_match('/\/\.git\//', $r)) continue;
		$a20[] = $r;
	} unset($a);

	$this->awog = $a20;
	
	$rootsz = strlen($this->droot);
	$i = 0;

	foreach($a20 as $r) {

		if (!trim($r)) continue;

		$re = '/(\S+)\s+(\S+.*)/';
		preg_match($re, $r, $ms);
		$hu = $ms[1];
		$hu = str_replace('+', ' ', $hu);
		$ts = strtotime($hu);
		$rp = $ms[2];
		$p = substr($rp, $rootsz);
		
		$this->theps[] = $p;
	}
} // func
} // class

sortSiteByTime::getPaths();


