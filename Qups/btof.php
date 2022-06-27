<?php

require_once('qanonStatus.php');

class qanonBackToFrontClass {
       public function __construct() {
               $this->do10();
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
                                               <!-- <td>$r[lm_hu]</td> -->
                                               <td>$r[asof_hu]</td>
                                               <td>$r[len_hu]</td>
                                               <td>$r[etag]</td>                                                       
                                       </tr>
HTQTR;
               }
               
               return $ht;
       }
      
}

if (didAnyCallMe(__FILE__)) new qanonBackToFrontClass();
