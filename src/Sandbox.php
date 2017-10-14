<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Category;
use Attogram\SharedMedia\Api\File;
use Attogram\SharedMedia\Api\Page;
use Attogram\SharedMedia\Api\Sources;

class Sandbox
{
    const VERSION = '0.9.7';

    public $methods = [
                ['Category', 'search'],
                ['Category', 'members'],
                ['Category', 'info'],
                //['Category', 'infoFromPageid'],
                //['Category', 'infoFromTitle'],
                ['Category', 'subcats'],
                ['Category', 'from'],
                ['File',     'search'],
                ['File',     'infoFromPageid'],
                ['File',     'infoFromTitle'],
                ['Page',     'search'],
            ];
    public $php_self;
    public $class;
    public $method;
    public $arg;
    public $endpoint;
    public $limit;

    public function __construct()
    {
        $this->sandboxInit();
        print $this->sandboxHeader();
        print $this->menu();
        print $this->form();
        print '<pre>';
        print $this->getResponse();
        print '</pre>';
        print $this->sandboxFooter();
    }

    public function sandboxInit()
    {
        $this->php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : null;
        $this->endpoint = isset($_GET['endpoint']) ? $_GET['endpoint'] : null;
        $this->limit = isset($_GET['limit']) ? $_GET['limit'] : null;
        $this->class = isset($_GET['class']) ? $_GET['class'] : null;
        $this->method = isset($_GET['method']) ? $_GET['method'] : null;
        $this->arg = isset($_GET['arg']) ? $_GET['arg'] : null;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function sandboxHeader($title = 'Sandbox')
    {
        $header = '<!DOCTYPE html><html><head><meta charset="UTF-8">'
        .'<title>'.$title.'</title>';
        $css = './sandbox.css';
        if (file_exists($css)) {
            $header .= '<style>'.file_get_contents('./sandbox.css').'</style>';
        }
        $header .='</head><body><h1><a href="'.$this->php_self.'">Sandbox</a>'
        .' / <a href="./">share-media-api</a></h1>';
        return $header;
    }

    public function sandboxFooter()
    {
        return '<footer><br /><hr />'
        .'<a href="'.$this->php_self.'">Sandbox</a> / <a href="./">share-media-api</a>'
        .'<small><pre>'
        .'Attogram\SharedMedia\Api\Api      v'. \Attogram\SharedMedia\Api\Api::VERSION
        .'<br />Attogram\SharedMedia\Api\Category v'. \Attogram\SharedMedia\Api\Category::VERSION
        .'<br />Attogram\SharedMedia\Api\File     v'. \Attogram\SharedMedia\Api\File::VERSION
        .'<br />Attogram\SharedMedia\Api\Page     v'. \Attogram\SharedMedia\Api\Page::VERSION
        .'<br />Attogram\SharedMedia\Api\Tools    v'. \Attogram\SharedMedia\Api\Tools::VERSION
        .'<br />Attogram\SharedMedia\Api\Sources  v'. \Attogram\SharedMedia\Api\Sources::VERSION
        .'<br />Attogram\SharedMedia\Api\Sandbox  v'. self::VERSION
        .'<br />GuzzleHttp\Client                 v'. \GuzzleHttp\Client::VERSION
        .'<br />Monolog\Logger                    API v'. \Monolog\Logger::API
        .'</pre></small>'
        .'</footer></body></html>';
    }

    public function sandboxResult($results = [])
    {
            return htmlentities(print_r($results, true));
    }

    public function menu()
    {
        $lastClass = null;
        $menu = '<p>';
        foreach ($this->methods as list($class, $method)) {
            if (!empty($lastClass) && $lastClass != $class) {
                $menu .= ' &nbsp; ';
            }
            $menu .= '<div class="menu">'
                .'<a href="'.$this->php_self.'?class='.$class.'&amp;method='.$method.'">'
                .$class.'::'.$method.'</a></div>';
            $lastClass = $class;
        }
        $menu .= '</p>';
        return $menu;
    }

    public function form()
    {
        if (!$this->class || !$this->method) {
            return;
        }
        if (array_search([$this->class,$this->method], $this->methods) === false) {
            return 'ERROR: form: class::method not found';
        }
        return '<p><form>'
            .'<input type="hidden" name="class" value="'.$this->class.'" />'
            .'<input type="hidden" name="method" value="'.$this->method.'" />'
            .$this->apiForm()
            .$this->class.'::'.$this->method.': <input name="arg" type="text" size="30" value="" />'
            .'<br /><br />'
            .'<input type="submit" value="                     GO                     "/>'
            .'</form></p>';
    }

    public function apiForm()
    {
        $class = $this->getClass();
        $form = '';
        $form .= 'API Endpoint: <select name="endpoint">';
        foreach (Sources::$sources as $key => $source) {
            $select = '';
            if (isset($this->endpoint) && $this->endpoint == $source) {
                $select = ' selected ';
            }
            $form .= '<option value="'.$source.'"'.$select.'>'.$key.' -- '.$source.'</option>';
        }
        $form .= '</select>'
        .'<br />'
        .'API limit: <input name="limit" value="'.$class::MAX_LIMIT.'" type="text" size="5" />'
        .'<br />';
        return $form;
    }

    public function getResponse()
    {
        if (!$this->class || !$this->method || !$this->arg) {
            return 'Welcome to the API Sandbox';
        }
        $class = $this->getClass();
        if (!method_exists($class, $this->method)) {
            return 'ERROR: Class::Method not found';
        }

        $class->setEndpoint($this->endpoint);
        $class->setLimit($this->limit);

        $method = $this->method;
        $arg = urldecode($this->arg) ?: '';
        //$class->log->debug(get_class($class).'::'.$method.'('.$arg.')');
        return $this->sandboxResult($class->$method($arg));
    }

    public function getClass()
    {
        switch ($this->class) {
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
