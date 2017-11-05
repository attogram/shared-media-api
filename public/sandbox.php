<?php // attogram/shared-media-api - sandbox.php - v1.0.0

use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Sandbox\Sandbox;

$autoload = '../vendor/autoload.php';
if (!is_readable($autoload)) {
    print 'ERROR: Autoloader Not Found: ' . $autoload;
    return false;
}
require_once($autoload);

if (!class_exists('Attogram\SharedMedia\Sandbox\Sandbox')) {
    print 'ERROR: Sandbox Class Not Found';
    return false;
}

$sandbox = new Sandbox();

$sandbox->setTitle('shared-media-api');

$sandbox->setMethods([
    // Class, Method, Arg, Identifiers
    ['Attogram\SharedMedia\Api\Category', 'search',              'query',  false],
    ['Attogram\SharedMedia\Api\Category', 'info',                false,    true],
    ['Attogram\SharedMedia\Api\Category', 'subcats',             false,    true],
    ['Attogram\SharedMedia\Api\Category', 'getCategoryfromPage', false,    true],
    ['Attogram\SharedMedia\Api\Media',    'search',              'query',  false],
    ['Attogram\SharedMedia\Api\Media',    'info',                false,    true],
    ['Attogram\SharedMedia\Api\Media',    'getMediaInCategory',  false,    true],
    ['Attogram\SharedMedia\Api\Media',    'getMediaOnPage',      false,    true],
    ['Attogram\SharedMedia\Api\Page',     'search',              'query',  false],
]);

$sandbox->setVersions([
    'Attogram\SharedMedia\Api\Transport',
    'Attogram\SharedMedia\Api\Base',
    'Attogram\SharedMedia\Api\Category',
    'Attogram\SharedMedia\Api\Media',
    'Attogram\SharedMedia\Api\Page',
    'Attogram\SharedMedia\Api\Tools',
    'Attogram\SharedMedia\Api\Sources',
    'Attogram\SharedMedia\Sandbox\Sandbox',
    'Attogram\SharedMedia\Sandbox\Tools',
    'Attogram\SharedMedia\Sandbox\Logger',
]);

$sandbox->setSources(Sources::$sources);

$sandbox->play();
