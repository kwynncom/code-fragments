<?php

function getDOM(string $t) : object {
    $o = new DOMDocument();
    libxml_use_internal_errors(true);
    $o->loadHTML($t); unset($t);
    libxml_clear_errors();	
    return $o;

}

