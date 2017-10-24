<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;
use Attogram\SharedMedia\Api\Category;

/**
 * Media file object
 */
class Media extends Base
{
    const VERSION = '0.10.0';

    /**
     * search for Media files
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        if (!Tools::isGoodString($query)) {
            $this->logger->error('Media::search: invalid query');
            return [];
        }
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::MEDIA_NAMESPACE);
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        return $this->getImageinfoResponse();
    }

    /**
     * get Media file information
     *
     * @return array
     */
    public function info()
    {
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        return $this->getImageinfoResponse();
    }

    /**
     * get Media files embedded on a Page
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @return array
     */
    public function onPage()
    {
        $this->logger->debug('Media::onPage');
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        return $this->getImageinfoResponse();
    }

    public function inCategory()
    {
        $this->logger->debug('Media::inCategory');
        $category = new Category($this->logger);
        $category->pageid = $this->pageid;
        $category->title = $this->title;
        return $category->members();
    }
}
