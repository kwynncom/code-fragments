<?php

interface tmate_config {
	const sessdir = '/var/kwynn/tmate/sessions/';
	const filepfx = 'tmate_ssh_';
	const metafn = 'meta_';
	const fpfx = self::sessdir . self::filepfx;
	const minstrlen = 16; // low number for testing
	const re10 = '/ssh session: ssh \S{15,80}\b/'; // use printf rather than echo to test \n
	const sfxn = 5;
	const sfx  = '.txt';
	
	// This is the limit for the initial ssh session info - what comes after n seconds
	const maxstrlen  = 10 * 1000; // This should be a safe number, and there should be some limit.
}
