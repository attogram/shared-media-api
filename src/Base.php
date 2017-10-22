<?php

namespace Attogram\SharedMedia\Api;

/**
 * Base Object
 */
class Base extends Api
{
    const VERSION = '0.9.7';

    const DEFAULT_LIMIT = 50;

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
            return $this->setIdentifierValue('pageid', $prefix, $postfix);
        }
        return $this->setIdentifierValue('title', $prefix, $postfix);
    }

    /**
     * @param string $type 'pageid' or 'title'
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    private function setIdentifierValue($type, $prefix = '', $postfix = '')
    {
        $this->logger->debug('Base::setIdentifierValue');
        if (!in_array($type, ['pageid', 'title'])) {
            $this->logger->error('Base::setIdentifierValue: invalid type');
            return false;
        }
        $this->setParam($prefix.$type.$postfix, Tools::valuesImplode($this->{$type}));
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
