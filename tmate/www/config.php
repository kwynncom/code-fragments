<?php

require_once('/opt/kwynn/kwutils.php');
require_once(__DIR__ . '/utils.php');

interface tmate_config {
	const basedir = '/var/kwynn/tmate/sessions/';
	const sessdir = self::basedir . 'raw/';
	const ipaddir   = self::basedir . 'ipdate/';
	const hashdir   = self::basedir . 'iptmhash/';
	const byodir    = self::basedir . 'byrawgeo/';
	const hu        = 'Y-m-d-H:i';
	const filepfx = 'tmate_ssh_';
	const metafn = 'meta_';
	const fpfx = self::sessdir . self::filepfx;
	const minstrlen = 16; // low number for testing
	const minsshklen = 15;
	const resrw = '/ssh session: ssh (\S{' . self::minsshklen . ',80})\b/'; // use printf rather than echo to test \n
	const shksare = '/^\S{' . self::minsshklen . ',80}/';
	const sfxn = 5;
	const sfx  = '.txt';
	const maxfnstrlen = 60;
	
	// This is the limit for the initial ssh session info - what comes after n seconds
	const maxstrlen  = 10 * 1000; // This should be a safe number, and there should be some limit.
}

