<?php // see https://kwynn.com/t/7/11/blog.html#e2022_0122_mfd_10
// /opt/kwynn is a clone of https://github.com/kwynncom/kwynn-php-general-utils

// require_once('/opt/kwynn/mongodb3.php'); // first make sure the debugger gets past this line
// require_once('/doesnotexist'); // for sake of contrast, this should fail
// then comment out the mongodb3 line and make sure this works:
require_once('/opt/kwynn/kwutils.php'); 
// then this:
$oid = dao_generic_3::get_oids();
// I suppose if you must finally use output to debug, as opposed to the variable watch in the debugger
echo($oid . "\n");
// the result should be something with the form of:
// 0122-1830-2022-02s-d1c818692923aa71
// month day hour minute year second / 24 bits of a sequence starting at a random point and then more bits based on the machine / process
