<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;
use Attogram\SharedMedia\Api\Category;

/**
 * File object
 */
class File extends Base
{
    const VERSION = '0.9.12';

    /**
     * search for Files
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        if (!Tools::isGoodString($query)) {
            $this->logger->error('File::search: invalid query');
            return [];
        }
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::FILE_NAMESPACE);
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        return $this->getInfoResponse();
    }

    /**
     * get File information
     *
     * @return array
     */
    public function info()
    {
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        return $this->getInfoResponse();
    }

    /**
     * get Files embedded on a Page
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @return array
     */
    public function onPage()
    {
        $this->logger->debug('File::onPage');
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        return $this->getInfoResponse();
    }

    public function inCategory()
    {
        $this->logger->debug('File::inCategory');
        $category = new Category($this->logger);
        $category->pageid = $this->pageid;
        $category->title = $this->title;
        return $category->members('file');
    }
}
