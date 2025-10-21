<?php

require_once('/opt/kwynn/kwutils.php');

interface hoursIntf {
    const host = 'localhost';
    const port = 8000;
    const filePath = '/var/kwynn/hours/';
    const glob     = self::filePath . '*.ods';
}