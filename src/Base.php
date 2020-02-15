<?php

namespace Attogram\SharedMedia\Api;

/**
 * Base Object
 */
class Base extends Transport
{
    const VERSION = '1.1.5';

    const DEFAULT_LIMIT = 10;

    const CATEGORY_NAMESPACE = 14;
    const MEDIA_NAMESPACE = 6;
    const PAGE_NAMESPACE = 0;

    protected $thumbnailWidth = 125;

    private $pageid;
    private $title;
    private $limit;

    /**
     * @param int|null $pageid
     */
    public function setPageid($pageid = null)
    {
        $this->logger->debug('Base:setPageid', [$pageid]);
        $this->pageid = $pageid;
    }

    /**
     * @param string|null $title
     */
    public function setTitle($title = null)
    {
        $this->logger->debug('Base:setTitle', [$title]);
        $this->title = $title;
    }

    /**
     * @param string|null $prefix
     * @param string|null $postfix
     * @return bool
     */
    protected function setIdentifier($prefix = '', $postfix = '')
    {
        $this->logger->debug('Base:setIdentifier');
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
        $this->logger->debug('Base:setIdentifierValue');
        if (!in_array($type, ['pageid', 'title'])) {
            $this->logger->error('Base::setIdentifierValue: invalid type');
            return false;
        }
        $this->setParam($prefix.$type.$postfix, Tools::valuesImplode($this->{$type}));
        return true;
    }

    /**
     * Set limit on # of responses for an API call
     *
     * @param int $limit
     * @return void
     */
    public function setLimit($limit)
    {
        $this->logger->debug('Base:setLimit', [$limit]);
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        $this->logger->debug('Base:getLimit', [$this->limit]);
        if (!$this->limit) {
            $this->logger->info('Base:getLimit: setting DEFAULT_LIMIT');
            $this->setLimit(self::DEFAULT_LIMIT);
        }
        return $this->limit;
    }

    /**
     * @see https://www.mediawiki.org/wiki/API:Search
     *
     * @param string $query
     * @param int|null $namespace
     * @return void
     */
    protected function setGeneratorSearch($query, $namespace = null)
    {
        $this->logger->debug('Base:setGeneratorSearch');
        $this->setParam('generator', 'search');
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        if ($namespace) {
            $this->setParam('gsrnamespace', $namespace);
        }
    }

    /**
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     *
     * @return void
     */
    protected function setGeneratorCategorymembers()
    {
        $this->logger->debug('Base:setGeneratorCategorymembers');
        $this->setParam('generator', 'categorymembers');
        $this->setParam('gcmprop', 'ids|title|type|timestamp');
        $this->setParam('gcmlimit', $this->getLimit());
    }

    /**
     * @see https://www.mediawiki.org/wiki/API:Categories
     *
     * @return void
     */
    protected function setGeneratorCategories()
    {
        $this->logger->debug('Base:setGeneratorCategories');
        $this->setParam('generator', 'categories');
        $this->setParam('gclprop', 'hidden|timestamp');
        $this->setParam('gcllimit', $this->getLimit());
    }

    /**
     * @see https://www.mediawiki.org/wiki/API:Images
     *
     * @return void
     */
    protected function setGeneratorImages()
    {
        $this->logger->debug('Base:setGeneratorImages');
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
    }

    /**
     * Set API parameters for an imageinfo query
     *
     * @see https://www.mediawiki.org/wiki/API:Imageinfo
     * @return void
     */
    protected function setImageinfoParams()
    {
        $this->logger->debug('Base:setImageinfoParams');
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
    protected function getImageinfoResponse()
    {
        $this->logger->debug('Base:getImageinfoResponse');
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * @param string $cmtype 'file' or 'subcat'
     * @return array
     */
    protected function getCategorymemberResponse($cmtype)
    {
        $this->logger->debug('Base:getCategorymemberResponse');
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
            default:
                return [];
        }
    }

    /**
     * @see https://www.mediawiki.org/wiki/API:Categoryinfo
     *
     * @return array
     */
    protected function getCategoryinfoResponse()
    {
        $this->logger->debug('Base:getCategoryinfoResponse');
        $this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
