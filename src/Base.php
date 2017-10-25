<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Base Object
 */
class Base extends Transport
{
    const VERSION = '0.10.0';

    const DEFAULT_LIMIT = 50;

    const CATEGORY_NAMESPACE = 14;
    const MEDIA_NAMESPACE = 6;
    const PAGE_NAMESPACE = 0;

    public $pageid;
    public $title;

    public $thumbnailWidth = 100;

    private $limit;

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    public function setIdentifier($prefix = '', $postfix = '')
    {
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
     * @param string $query
     * @param int|null $namespace
     * @return void
     */
    public function setGeneratorSearch($query, $namespace = null)
    {
        $this->setParam('generator', 'search');
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        if ($namespace) {
            $this->setParam('gsrnamespace', $namespace);
        }
    }

    /**
     * @return void
     */
    public function setGeneratorCategorymembers()
    {
        $this->setParam('generator', 'categorymembers');
        $this->setParam('gcmprop', 'ids|title');
        $this->setParam('gcmlimit', $this->getLimit());
    }

    /**
     * @return void
     */
    public function setGeneratorCategories()
    {
        $this->setParam('generator', 'categories');
        $this->setParam('gclprop', 'hidden|timestamp');
        $this->setParam('gcllimit', $this->getLimit());
    }

    /**
     * @return void
     */
    public function setGeneratorImages()
    {
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
    }

    /**
     * Set API parameters for an imageinfo query
     *
     * @see https://www.mediawiki.org/wiki/API:Imageinfo
     * @return void
     */
    public function setImageinfoParams()
    {
        $this->setParam('prop', 'imageinfo');
        $this->setParam('iiprop', 'url|size|mime|thumbmime|user|userid|sha1|timestamp|extmetadata');
        $this->setParam('iiextmetadatafilter', 'LicenseShortName|UsageTerms|AttributionRequired|'
                        .'Restrictions|Artist|ImageDescription|DateTimeOriginal');
        $this->setParam('iiurlwidth', $this->thumbnailWidth);
    }

    /**
     * Get API response from a files-info request
     *
     * @return array
     */
    public function getImageinfoResponse()
    {
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * @param string $cmtype 'file' or 'subcat'
     * @return array
     */
    public function getCategorymemberResponse($cmtype)
    {
        if (!$this->setIdentifier('gcm', '')) {
            return [];
        }
        $this->setGeneratorCategorymembers();
        $this->setParam('gcmtype', $cmtype);
        switch ($cmtype) {
            case 'subcat':
                return $this->getCategoryinfoResponse();
            case 'file':
                return $this->getImageinfoResponse();
        }
    }

    /**
     * @return array
     */
    public function getCategoryinfoResponse()
    {
        $this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
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
