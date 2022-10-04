<?php

require_once('/opt/kwynn/kwutils.php');

class jscssht {
	
	const jsDefault = '/opt/kwynn/js/utils.js';
	
	private static function setDirs(&$dr, &$url, &$base) {
		$dr   = $_SERVER['DOCUMENT_ROOT'];	
		
		if ($rl = readlink($dr)) $dr = $rl;
		
		$url =  dirname($_SERVER['REQUEST_URI']);
		if (!$base) $base = $url;
		
		if ($rl20 = readlink($dr . $url)) $base = $rl20;
	}
	

	public static function echoAll(string $base = '') {


		self::setDirs($dr, $url, $base);
		

		$tys = ['css' => '/^.*\.css$/', 'js' => '/^.*\.js$/'];

		foreach($tys as $ext => $re) 
		{
			$fs = [];

			if ($ext === 'js' && is_readable(self::jsDefault)) $fs[] = self::jsDefault;

			$fs = kwam($fs, self::recursiveSearch($base, $re ));

			foreach($fs as $f) {
				$d = str_replace($dr, '', $f);
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