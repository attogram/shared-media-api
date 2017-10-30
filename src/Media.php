<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Media file object
 */
class Media extends Base
{
    const VERSION = '0.10.3';

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
        return $this->getImageinfoResponse(/*setIdentifier=*/true);
    }

    /**
     * get Media files embedded on a Page
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @return array
     */
    public function getMediaOnPage()
    {
        $this->setGeneratorImages();
        return $this->getImageinfoResponse(/*setIdentifier=*/true);
    }

    /**
     * get a list of Media files in a category
     *
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     * @return array
     */
    public function getMediaInCategory()
    {
        return $this->getCategorymemberResponse('file');
    }
}
