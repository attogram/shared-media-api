<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Category;
use Attogram\SharedMedia\Api\File;
use Attogram\SharedMedia\Api\Page;
use Attogram\SharedMedia\Api\Sources;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Sandbox
{
    const VERSION = '0.9.18';

    const DEFAULT_LIMIT = 10;

    public $methods = [
                ['Category', 'search'],
                ['Category', 'members'],
                ['Category', 'info'],
                ['Category', 'subcats'],
                ['Category', 'from'],
                ['File',     'search'],
                ['File',     'infoFromPageid'],
                ['File',     'infoFromTitle'],
                ['Page',     'search'],
            ];
    public $self;
    public $class;
    public $method;
    public $arg;
    public $endpoint;
    public $limit;
    public $logLevel;
    public $logger;

    public function __construct()
    {
        $this->sandboxInit();
        $this->sandboxDefaults();
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
        $this->self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : null;
        $this->endpoint = isset($_GET['endpoint']) ? trim(urldecode($_GET['endpoint'])) : null;
        $this->limit = isset($_GET['limit']) ? trim(urldecode($_GET['limit'])) : null;
        $this->class = isset($_GET['class']) ? trim(urldecode($_GET['class'])) : null;
        $this->method = isset($_GET['method']) ? trim(urldecode($_GET['method'])) : null;
        $this->arg = isset($_GET['arg']) ? trim(urldecode($_GET['arg'])) : null;
        $this->logLevel = isset($_GET['logLevel']) ? strtoupper(trim(urldecode($_GET['logLevel']))) : null;
    }

    public function sandboxDefaults()
    {
        if (!$this->limit) {
            $this->limit = self::DEFAULT_LIMIT;
        }
        if (!$this->logLevel) {
            $this->logLevel = 'NOTICE';
        }
        $this->logger = new Logger('Log');
        $this->logger->pushHandler(new StreamHandler('php://output', $this->getLogerLevel()));
    }

    public function getLogerLevel()
    {
        if (defined('\Monolog\Logger::'.$this->logLevel)) {
            return constant('\Monolog\Logger::'.$this->logLevel);
        }
        return Logger::DEBUG;
    }

    public function sandboxHeader()
    {
        return '<!DOCTYPE html><html><head>'
        .'<meta charset="UTF-8">'
        .'<meta name="viewport" content="initial-scale=1" />'
        .'<meta http-equiv="X-UA-Compatible" content="IE=edge" />'
        .'<link rel="stylesheet" type="text/css" href="sandbox.css" />'
        .'<title>shared-media-api / sandbox</title>'
        .'</head><body><h1><a href="./">shared-media-api</a></h1> <h2><a href="'.$this->self.'">sandbox</a></h2>';
    }

    public function sandboxFooter()
    {
        return '<footer><br /><hr />'
        .'<a href="./">shared-media-api</a> : <a href="'.$this->self.'">sandbox</a>'
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
                .'<a href="'.$this->self.'?class='.$class.'&amp;method='.$method.'">'
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
            .'<p><b>'.$this->class.'::'.$this->method.'</b>: '
            .'<input name="arg" type="text" size="42" value="'.$this->arg.'" /></p>'
            .'<input type="submit" value="                  GO                  "/>'
            .'</form></p>';
    }

    public function apiForm()
    {
        return 'Endpoint: '.$this->endpointSelect()
        .'<br />Limit: <input name="limit" value="'.$this->limit.'" type="text" size="5" />'
        .'<br />Log Level: '.$this->logLevelSelect();
    }

    public function endpointSelect()
    {
        $select = '<select name="endpoint">';
        foreach (Sources::$sources as $key => $source) {
            $selected = '';
            if (isset($this->endpoint) && $this->endpoint == $source) {
                $selected = ' selected ';
            }
            $select .= '<option value="'.$source.'"'.$selected.'>'.$key.' -- '.$source.'</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function logLevelSelect()
    {
        return '<select name="logLevel">'
        .'<option value="debug"'.$this->isSelected('DEBUG', $this->logLevel).'>debug</option>'
        .'<option value="info"'.$this->isSelected('INFO', $this->logLevel).'>info</option>'
        .'<option value="notice"'.$this->isSelected('NOTICE', $this->logLevel).'>notice</option>'
        .'<option value="warning"'.$this->isSelected('WARNING', $this->logLevel).'>warning</option>'
        .'<option value="error"'.$this->isSelected('ERROR', $this->logLevel).'>error</option>'
        .'<option value="critical"'.$this->isSelected('CRITICAL', $this->logLevel).'>critical</option>'
        .'<option value="alert"'.$this->isSelected('ALERT', $this->logLevel).'>alert</option>'
        .'<option value="emergency'.$this->isSelected('EMERGENCY', $this->logLevel).'">emergency</option>'
        .'</select>';
    }

    public function isSelected($str1, $str2)
    {
        if ($str1 == $str2) {
            return ' selected ';
        }
    }

    public function getResponse()
    {
        if (!$this->class || !$this->method || !$this->arg) {
            return;
        }
        $class = $this->getClass();
        if (!method_exists($class, $this->method)) {
            return 'ERROR: Class::method not found';
        }
        $class->setEndpoint($this->endpoint);
        $class->setLimit($this->limit);
        return $this->sandboxResult($class->{$this->method}($this->arg));
    }

    public function getClass()
    {
        switch ($this->class) {
            case 'Category':
                return new Category($this->logger);
            case 'File':
                return new File($this->logger);
            case 'Page':
                return new Page($this->logger);
            default:
                return new \StdClass();
        }
    }
}
