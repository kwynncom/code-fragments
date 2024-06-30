<?php

require_once('/opt/kwynn/kwutils.php');

kwnohup('php c.php');
sleep(1);
kwnohup('php b.php');
