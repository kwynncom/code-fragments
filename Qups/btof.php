<?php

require_once('qanonStatus.php');

class qanonBackToFrontClass {
       public function __construct() {
               $this->do10();
       }
       
	   public function getMeta() {
		   $ht  = '';
		   $ht .= 'fetch time: ' . $this->getFetchTime() . 'ms; attempt at: ' . date('r');
	   }
	   
       public function getFetchTime() { return $this->fetchms; }
       
       private function do10() {
               $a = Qupdates::get();
               $this->fetchms = $a['fetch_ms'];
               $this->thedat  = $a['dat'];
               return;
       }
       
       public function getHTRows() {
               
               $d = $this->thedat;
               
               $ht = '';
               foreach($d as $r) {
                       $ht .= <<<HTQTR
                                       <tr>
                                         <td>$r[etag]</td>           
   <td>$r[lm_hu]</td>
                                               <td>$r[asof_hu]</td>
                                               <!-- <td>$r[len_hu]</td> -->
                                                           
                                       </tr>
HTQTR;
               }
               
               return $ht;
       }
      
}

if (didAnyCallMe(__FILE__)) new qanonBackToFrontClass();
