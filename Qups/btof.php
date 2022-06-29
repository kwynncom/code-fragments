<?php

require_once('qanonStatus.php');

class qanonBackToFrontClass {
       public function __construct() {
               $this->do10();
       }
       
	   public function getMeta() {
		   $ht  = '';
		   $ht .= 'fetch time: ' . $this->getFetchTime() . 'ms; attempt at: ' . date('r');
		   return $ht;
	   }
	   
       public function getFetchTime() { return $this->fetchms; }
       
       private function do10() {
               $a = Qupdates::get();
               $this->fetchms = $a['fetch_ms'];
               $this->thedat  = $a['dat'];
               return;
       }
	   
	   private function d10($rin) {
		   $a = $rin;
		   
		   $a['asof_d10'] = date('m/d H:i:s', $a['asof_ts']);
		   $a['lm_d10']   = date('m/d H:i', strtotime($a['lm_hu']));
		   $e = $a['etag'];
		   $e = str_replace('-', '', $e);
		   $e = str_replace('"', '', $e);
		   $a['etag_d10'] = $e;
		   
		   return $a;
		   
	   }
       
       public function getHTRows() {
               
               $d = $this->thedat;
               
			   $i = 0;
               $ht = '';
               foreach($d as $rr) {
				   
				   if ($i++ === 0) $cl1 = 'tdlen1';
				   else $cl1 = '';
				   
				   $r = $this->d10($rr);
				   
                   $ht .= <<<HTQTR
<tr>
	<td class='$cl1'>$r[len_hu]</td>
	<td>$r[etag_d10]</td>           
	<td>$r[lm_d10]</td>
    <td>$r[asof_d10]</td>
</tr>
HTQTR;
               }
               
               return $ht;
       }
      
}

if (didAnyCallMe(__FILE__)) new qanonBackToFrontClass();
