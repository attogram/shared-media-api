<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Api;
use Attogram\SharedMedia\Api\Base;
use Attogram\SharedMedia\Api\Category;
use Attogram\SharedMedia\Api\File;
use Attogram\SharedMedia\Api\Page;
use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Api\Tools;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Sandbox
{
    const VERSION = '0.9.36';

    const DEFAULT_LIMIT = 10;

    public $methods = [ // Class, Method, Has Arg, Use Identifiers

        ['Category', 'search',     'query',  false],
        ['Category', 'info',       false,    true],
        ['Category', 'subcats',    false,    true],
        ['Category', 'members',    false,    true],
        ['Category', 'fromPage',   false,    true],

        ['File',     'search',     'query',  false],
        ['File',     'info',       false,    true],
        ['File',     'inCategory', false,    true],
        ['File',     'onPage',     false,    true],

        ['Page',     'search',     'query',  false],
    ];
    public $self;
    public $class;
    public $method;
    public $arg;
    public $endpoint;
    public $limit;
    public $logLevel;
    public $logger;
    public $isSubmitted;
    public $pageids;
    public $titles;

    public function play()
    {
        $this->sandboxInit();
        $this->sandboxDefaults();
        print $this->getHeader().'<br />'.$this->menu().$this->form();
        if ($this->isSubmitted) {
            print '<pre>';
            print $this->getResponse();
            print '</pre>';
        }
        print $this->getFooter();
    }

    public function sandboxInit()
    {
        $this->self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : null;
        $this->endpoint = Tools::getGet('endpoint');
        $this->limit = Tools::getGet('limit');
        $this->class = Tools::getGet('class');
        $this->method = Tools::getGet('method');
        $this->arg = Tools::getGet('arg');
        $this->pageids = Tools::getGet('pageids');
        $this->titles = Tools::getGet('titles');
        $this->logLevel = Tools::getGet('logLevel');
        $this->isSubmitted = isset($_GET['play']) ? true : false;
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
        $this->logger->pushHandler(new StreamHandler('php://output', $this->logLevel));
    }

    public function getHeader()
    {
        return '<!DOCTYPE html><html><head>'
        .'<meta charset="UTF-8">'
        .'<meta name="viewport" content="initial-scale=1" />'
        .'<meta http-equiv="X-UA-Compatible" content="IE=edge" />'
        .'<link rel="stylesheet" type="text/css" href="sandbox.css" />'
        .'<title>shared-media-api / sandbox</title>'
        .'</head><body><h1><a href="./">shared-media-api</a></h1><h2><a href="'.$this->self.'">sandbox</a></h2>';
    }

    public function getFooter()
    {
        return '<footer><hr />'
        .'<a href="./">shared-media-api</a> : <a href="'.$this->self.'">sandbox</a>'
        .'<pre>Attogram\SharedMedia\Api'
        .'<br />Api      v'.Api::VERSION
        .'<br />Base     v'.Base::VERSION
        .'<br />Category v'.Category::VERSION
        .'<br />File     v'.File::VERSION
        .'<br />Page     v'.Page::VERSION
        .'<br />Tools    v'.Tools::VERSION
        .'<br />Sources  v'.Sources::VERSION
        .'<br />Sandbox  v'. self::VERSION
        .'</pre>'
        .'</footer></body></html>';
    }

    public function menu()
    {
        $lastClass = null;
        $menu = '';
        foreach ($this->methods as list($class, $method)) {
            if ($lastClass != $class) {
                $menu .= '</div><div class="menubox">'.$class.'::';
            }
            $menu .= '<div class="menu">'
                .'<a href="'.$this->self.'?class='.$class.'&amp;method='.$method.'">'.$method.'</a>'
                .'</div>';
            $lastClass = $class;
        }
        $menu = substr($menu, 6); // remove unmatched first </div>
        return $menu.'</div>';
    }

    public function getMethodInfo()
    {
        if (!$this->hasMethodInfo()) {
            return false;
        }
        foreach ($this->methods as $key => $val) {
            if ($val[0] == $this->class && $val[1] == $this->method) {
                return $this->methods[$key];
            }
        }
    }

    public function hasMethodInfo()
    {
        if ($this->class && $this->method) {
            return true;
        }
        return false;
    }

    public function form()
    {
        $action = $this->getMethodInfo();
        if (!$action) {
            return;
        }
        $form = '<form>'
            .'<input type="hidden" name="play" value="1" />'
            .'<input type="hidden" name="class" value="'.$this->class.'" />'
            .'<input type="hidden" name="method" value="'.$this->method.'" />'
            .$this->apiForm().'<br />';
        if ($action[3]) {
            $form .= $this->identifierForm().'<br />';
        }
        $form .= '<b>'.$this->class.'::'.$this->method.'</b>:';
        if ($action[2]) {
            $form .= '<input name="arg" type="text" size="42" value="'.$this->arg.'" />';
        }
        $form .= '<br /><input type="submit" value="                  GO                  "/></form>';
        return $form;
    }

    public function apiForm()
    {
        return 'endpoint:'.$this->endpointSelect()
        .'&nbsp; <nobr>limit:<input name="limit" value="'.$this->limit.'" type="text" size="5" /></nobr>'
        .'&nbsp; <nobr>logLevel:'.$this->logLevelSelect().'</nobr>';
    }

    public function identifierForm()
    {
        return 'Identifier: '
        . 'Titles:<input name="titles" value="'.$this->titles.'" type="text" size="30" />'
        . ' OR: '
        . 'Pageids:<input name="pageids" value="'.$this->pageids.'" type="text" size="30" />';
    }

    public function endpointSelect()
    {
        $select = '<select name="endpoint">';
        foreach (Sources::$sources as $source) {
            $selected = '';
            if (isset($this->endpoint) && $this->endpoint == $source) {
                $selected = ' selected ';
            }
            $select .= '<option value="'.$source.'"'.$selected.'>'.$source.'</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function logLevelSelect()
    {
        return '<select name="logLevel">'
        .'<option value="DEBUG"'.Tools::isSelected('DEBUG', $this->logLevel).'>debug</option>'
        .'<option value="INFO"'.Tools::isSelected('INFO', $this->logLevel).'>info</option>'
        .'<option value="NOTICE"'.Tools::isSelected('NOTICE', $this->logLevel).'>notice</option>'
        .'<option value="WARNING"'.Tools::isSelected('WARNING', $this->logLevel).'>warning</option>'
        .'<option value="ERROR"'.Tools::isSelected('ERROR', $this->logLevel).'>error</option>'
        .'<option value="CRITICAL"'.Tools::isSelected('CRITICAL', $this->logLevel).'>critical</option>'
        .'<option value="ALERT"'.Tools::isSelected('ALERT', $this->logLevel).'>alert</option>'
        .'<option value="EMERGENCY'.Tools::isSelected('EMERGENCY', $this->logLevel).'">emergency</option>'
        .'</select>';
    }

    public function getResponse()
    {
        $action = $this->getMethodInfo();
        if (!$action) {
            return 'ERROR: Class::method not allowed';
        }
        if ($action[2] && !$this->arg) {
            return 'ERROR: Missing Arg: '.$action[2];
        }
        $class = $this->getClass();
        if (!method_exists($class, $this->method)) {
            return 'ERROR: Class::method not found';
        }
        $class->pageid = $this->pageids;
        $class->title = $this->titles;
        $class->setEndpoint($this->endpoint);
        $class->setLimit($this->limit);
        $results = $class->{$this->method}($this->arg);
        return htmlentities(var_dump($results, true));
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
