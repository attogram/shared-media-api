<?php // attogram/shared-media-api - sandbox.php - v0.9.1

use Attogram\SharedMedia\Api\Sandbox;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'ERROR: Autoloader not found: ' . $autoload;
    return false;
}
require_once($autoload);

$sandbox = new Sandbox();
$sandbox->play();
