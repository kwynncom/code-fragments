<?php

require_once(__DIR__ . '/../commonFront.php');

abstract class IQTaskFront {
	protected function loadBack() {
		$n = isrv('n');
		require_once(IQTestIntf::backd . 't' . $n . 'bk.php');
		$cl = 'IQTask' . $n . 'Back';
		$this->getI($cl);

		return;		
	}
	
	private function getI(string $cn) {
		$o = new $cn;		
		$this->quaps = $o->quaps;
	}
	
	public function dbidjs() {
		$id = $this->quaps->_id;
		$s = <<<JSDBID
<script>
const DBID = '$id';
</script>
JSDBID;
		echo($s);
	}
}
