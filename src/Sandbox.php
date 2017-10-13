<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Category;
use Attogram\SharedMedia\Api\File;
use Attogram\SharedMedia\Api\Page;
use Attogram\SharedMedia\Api\Sources;

class Sandbox
{
    const VERSION = '0.9.5';

    public $methods;

    public function __construct()
    {
        $this->sandboxHeader();
        $this->menu();
        $this->form();
        print PHP_EOL.$this->getResponse();
        $this->sandboxFooter();
    }

    public function getMethods()
    {
        $this->methods = [
            ['Category', 'search', '(string) query'],
            ['Category', 'members', '(string) Category:Title'],
            ['Category', 'info', '(string) Category:Title | (string) Category:Title|...'],
            ['Category', 'subcats', '(string) Category:Title'],
            ['Category', 'from', '(int) pageid'],
            ['File', 'search', '(string) query'],
            ['File', 'infoFromPageid', '(int) pageid | (string) pageid|... | (array) pageids'],
            ['File', 'infoFromTitle', '(string) File:Title | (string) File:Title|... | (array) titles'],
            ['Page', 'search', '(string) query'],
        ];
        return $this->methods;
    }

    public function sandboxHeader($title = 'Sandbox')
    {
        print '<!DOCTYPE html><html><head><meta charset="UTF-8">'
        .'<title>'.$title.'</title>'
        .'<style>'
        .'a { text-decoration:none; }'
        .'form { padding:10px; border:1px solid #AAAAAA; background-color:#EEEEEE; }'
        .'input { font-family:monospace; padding:2; }'
        .'.menu { display:inline-block; border:1px solid #AAAAAA; background-color:#EEEEEE; margin:1px; padding:5px; }'
        .'</style>'
        .'</head><body><pre><b><a href="./">attogram/share-media-api</a>   '
        .'<a href="'.$_SERVER['PHP_SELF'].'">API Sandbox</a>'
        .'</b><br />';
    }

    public function sandboxFooter()
    {
        print '<pre><br /><br /><hr /><b><a href="./">attogram/shared-media-api</a></b>';
        print ' @ '.gmdate('Y-m-d H:i:s').' UTC<br />';
        print '<br />Attogram\SharedMedia\Api\Api      v'. \Attogram\SharedMedia\Api\Api::VERSION;
        print '<br />Attogram\SharedMedia\Api\Category v'. \Attogram\SharedMedia\Api\Category::VERSION;
        print '<br />Attogram\SharedMedia\Api\File     v'. \Attogram\SharedMedia\Api\File::VERSION;
        print '<br />Attogram\SharedMedia\Api\Page     v'. \Attogram\SharedMedia\Api\Page::VERSION;
        print '<br />Attogram\SharedMedia\Api\Tools    v'. \Attogram\SharedMedia\Api\Tools::VERSION;
        print '<br />Attogram\SharedMedia\Api\Sources  v'. \Attogram\SharedMedia\Api\Sources::VERSION;
        print '<br />Attogram\SharedMedia\Api\Sandbox  v'. self::VERSION;
        print '<br />GuzzleHttp\Client                 v'. \GuzzleHttp\Client::VERSION;
        print '<br />Monolog\Logger                    API v'. \Monolog\Logger::API;
        print '</pre></body></html>';
    }

    public function sandboxResult($results = [])
    {
            return htmlentities(print_r($results, true));
    }

    public function menu()
    {
        $lastClass = null;
        foreach ($this->getMethods() as list($class, $method, $info)) {
            if ($lastClass != $class) {
                print '<br />';
            }
            print '<div class="menu">'
            .'<a href="'.$_SERVER['PHP_SELF']
            .'?class='.$class.'&amp;method='.$method.'" title="'.$info.'">'
            .$class.'::'.$method
            .'</a></div>';
            $lastClass = $class;
        }
    }

    public function form()
    {
        if (!isset($_GET['class']) || !isset($_GET['method'])) {
            return;
        }
        foreach ($this->getMethods() as list($class, $method, $info)) {
            if ($class != $_GET['class'] || $method != $_GET['method']) {
                continue;
            }
            print '<form>'
            .'<input type="hidden" name="class" value="'.$class.'" />'
            .'<input type="hidden" name="method" value="'.$method.'" />'
            .$this->apiForm()
            .$class.'::'.$method.': <input name="arg" type="text" size="30" value="" />'
            .' <code>'.$info.'</code><br /><br />'
            .'<input type="submit" value="                     GO                     "/>'
            .'<br /></form>';
        }
    }

    public function apiForm()
    {
        $class = $this->getClass();
        $form = '';
        $form .= 'API Endpoint: <select name="endpoint">';
        foreach (Sources::$sources as $source) {
			$select = '';
			if (isset($_GET['endpoint']) && $_GET['endpoint'] == $source) {
				$select = ' selected ';
			}
            $form .= '<option value="'.$source.'"'.$select.'>'.$source.'</option>';
        }
        $form .= '</select>'
        .'<br />'
        .'API limit: <input name="limit" value="'.$class::MAX_LIMIT.'" type="text" size="5" />'
        .'<br />';
        return $form;
    }

    public function getResponse()
    {
        if (!isset($_GET['class']) || !isset($_GET['method']) || !isset($_GET['arg'])) {
            return 'Welcome to the API Sandbox';
        }
        $class = $this->getClass();
        if (!method_exists($class, $_GET['method'])) {
            return 'ERROR: Class::Method not found';
        }
		
		$class->setEndpoint($_GET['endpoint']);
        $method = $_GET['method'];
        $arg = urldecode($_GET['arg']) ?: '';
        //$class->log->debug(get_class($class).'::'.$method.'('.$arg.')');
        return $this->sandboxResult($class->$method($arg));
    }

    public function getClass()
    {
        switch ($_GET['class']) {
            case 'Category':
                return new Category;
            case 'File':
                return new File;
            case 'Page':
                return new Page;
            default:
                return new \StdClass();
        }
    }
}
