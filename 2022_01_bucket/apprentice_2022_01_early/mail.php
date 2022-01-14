<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '/opt/composer');
require_once('vendor/autoload.php');
if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) die('class does not exist');
echo('OK' . "\n");
