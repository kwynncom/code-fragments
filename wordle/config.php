<?php

require_once('/opt/kwynn/kwutils.php');

interface WordleConfig {
	const prefix = __DIR__ . '/list/wordle_list_2309_words.';
	const listJSON = self::prefix . 'json';
	const listTXT  = self::prefix . 'txt';
	const wordLength = 5;
			
}