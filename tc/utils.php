<?php

require_once('/opt/kwynn/kwutils.php');

class jscssht {
	
	const jsDefault = '/opt/kwynn/js/utils.js';


	public static function echoAll(string $base = '') {

		$url =  dirname($_SERVER['REQUEST_URI']);
		if (!$base) $base = $_SERVER['DOCUMENT_ROOT'] . $url;

		$tys = ['css' => '/^.*\.css$/', 'js' => '/^.*\.js$/'];

		foreach($tys as $ext => $re) 
		{
			$fs = [];

			if ($ext === 'js' && is_readable(self::jsDefault)) $fs[] = self::jsDefault;

			$fs = kwam($fs, self::recursiveSearch($base, $re ));

			foreach($fs as $f) {
				$d = str_replace($base, $url, $f);
				if      ($ext === 'css') $t = "<link rel='stylesheet' href='$d' />\n";
				else if ($ext === 'js' ) $t = "<script src='$d'></script>\n";   
				echo($t);
			}

		}
	}

	public static function recursiveSearch($dir, $re) {
		$return = [];
		$iti = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
		while($iti->valid()){
			$p = $iti->key();
			if (preg_match($re, $p, $ms)) {
				$return[] = $ms[0];
			}
			$iti->next();
		}
		return $return;
	}
}