<?php // attogram/shared-media-api - sandbox.php - v0.10.2

use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Sandbox\Sandbox;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'ERROR: Autoloader not found: ' . $autoload;
    return false;
}
require_once($autoload);

$sandbox = new Sandbox();

$sandbox->setTitle('shared-media-api');

$sandbox->setMethods([
	// Class, Method, Arg, Identifiers
	['Category', 'search',              'query',  false],
	['Category', 'info',                false,    true],
	['Category', 'subcats',             false,    true],
	['Category', 'getCategoryfromPage', false,    true],
	['Media',    'search',              'query',  false],
	['Media',    'info',                false,    true],
	['Media',    'getMediaInCategory',  false,    true],
	['Media',    'getMediaOnPage',      false,    true],
	['Page',     'search',              'query',  false],
]);

$sandbox->setVersions([
	'Attogram\SharedMedia\Api\Transport',
	'Attogram\SharedMedia\Api\Base',
	'Attogram\SharedMedia\Api\Category',
	'Attogram\SharedMedia\Api\Media',
	'Attogram\SharedMedia\Api\Page',
	'Attogram\SharedMedia\Api\Tools',
	'Attogram\SharedMedia\Api\Sources',
	'Attogram\SharedMedia\Api\Logger',
    'Attogram\SharedMedia\Sandbox\Sandbox',
    'Attogram\SharedMedia\Sandbox\Tools',
    'Attogram\SharedMedia\Sandbox\Logger',
]);

$sandbox->setSources(Sources::$sources);

$sandbox->play();
