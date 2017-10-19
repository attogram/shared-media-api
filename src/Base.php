<?php

namespace Attogram\SharedMedia\Api;

/**
 * Base Object
 */
class Base extends Api
{
    const VERSION = '0.9.4';

    const CATEGORY_NAMESPACE = 14;
    const FILE_NAMESPACE = 6;
    const PAGE_NAMESPACE = 0;

    public $pageid;
    public $title;
    private $limit;

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    public function setIdentifier($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifier');
        if (!$this->pageid && !$this->title) {
            $this->logger->error('Base::setIdentifier: Identifier Not Found');
            return false;
        }
        if ($this->pageid) {
            return $this->setIdentifierPageid($prefix, $postfix);
        }
        return $this->setIdentifierTitle($prefix, $postfix);
    }

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    public function setIdentifierPageid($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifierPageid');
        $pageid = Tools::valuesImplode($this->pageid);
        $this->setParam($prefix.'pageid'.$postfix, $pageid);
        return true;
    }

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    public function setIdentifierTitle($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifierTitle');
        $title = Tools::valuesImplode($this->title);
        $this->setParam($prefix.'title'.$postfix, $title);
        return true;
    }

    /**
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        $this->logger->debug('Base::setLimit:', [$limit]);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        if (!is_numeric($this->limit) || !$this->limit) {
            $this->setLimit(self::DEFAULT_LIMIT);
        }
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function isBatchcomplete()
    {
        return isset($this->response['batchcomplete']) ? true : false;
    }

    /**
     * @return string|false
     */
    public function getTotalhits()
    {
        return isset($this->response['query']['searchinfo']['totalhits'])
            ? $this->response['query']['searchinfo']['totalhits'] : false;
    }

    /**
     * @return mixed
     */
    public function getWarnings()
    {
        return isset($this->response['warnings'])
            ? $this->response['warnings'] : false;
    }

    /**
     * @return string|false
     */
    public function getContinue()
    {
        return isset($this->response['continue']['continue'])
            ? $this->response['continue']['continue'] : false;
    }

    /**
     * @return int|false
     */
    public function getSroffset()
    {
        return isset($this->response['continue']['sroffset'])
            ? $this->response['continue']['sroffset'] : false;
    }
}
