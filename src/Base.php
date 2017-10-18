<?php

namespace Attogram\SharedMedia\Api;

/**
 * Base Object
 */
class Base extends Api
{
    const VERSION = '0.9.1';

    const CATEGORY_NAMESPACE = 14;
    const FILE_NAMESPACE = 6;
    const PAGE_NAMESPACE = 0;

    public $pageid;
    public $title;

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    public function setIdentifier($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifier');
        if ($this->pageid && (is_string($this->pageid) || is_array($this->pageid))) {
            $this->setIdentifierPageid($prefix, $postfix);
            return true;
        }
        if ($this->title && (is_string($this->title) || is_array($this->title))) {
            $this->setIdentifierTitle($prefix, $postfix);
            return true;
        }
        $this->logger->error('Base::setIdentifier: Identifier Not Found');
        return false;
    }

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     */
    public function setIdentifierPageid($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifierPageid');
        $pageid = Tools::valuesImplode($this->pageid);
        $this->setParam($prefix.'pageid'.$postfix, $pageid);
    }

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     */
    public function setIdentifierTitle($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifierTitle');
        $title = Tools::valuesImplode($this->title);
        $this->setParam($prefix.'title'.$postfix, $title);
    }

    /**
     * @uses Api::$response
     * @return bool
     */
    public function isBatchcomplete()
    {
        return isset($this->response['batchcomplete']) ? true : false;
    }

    /**
     * @uses Api::$response
     * @return string|false
     */
    public function getTotalhits()
    {
        return isset($this->response['query']['searchinfo']['totalhits'])
            ? $this->response['query']['searchinfo']['totalhits'] : false;
    }

    /**
     * @uses Api::$response
     * @return mixed
     */
    public function getWarnings()
    {
        return isset($this->response['warnings'])
            ? $this->response['warnings'] : false;
    }

    /**
     * @uses Api::$response
     * @return string|false
     */
    public function getContinue()
    {
        return isset($this->response['continue']['continue'])
            ? $this->response['continue']['continue'] : false;
    }

    /**
     * @uses Api::$response
     * @return int|false
     */
    public function getSroffset()
    {
        return isset($this->response['continue']['sroffset'])
            ? $this->response['continue']['sroffset'] : false;
    }
}
