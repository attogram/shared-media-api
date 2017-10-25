<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;
use Attogram\SharedMedia\Api\Category;

/**
 * Media file object
 */
class Media extends Base
{
    const VERSION = '0.10.1';

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
        $this->setGeneratorSearch($query, self::MEDIA_NAMESPACE);
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
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setGeneratorImages();
        return $this->getImageinfoResponse();
    }

    /**
     * @return array
     */
    public function inCategory()
    {
        $this->logger->error('Media::inCategory IN DEV');
        return [];
    }
}
