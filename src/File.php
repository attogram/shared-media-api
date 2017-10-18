<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;
use Attogram\SharedMedia\Api\Category;

/**
 * File object
 */
class File extends Base
{
    const VERSION = '0.9.8';

    public $width = 100;

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
        $this->identifierRequired = false;
        return $this->getInfoResponse();
    }

    /**
     * get File information
     *
     * @return array
     */
    public function info()
    {
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
        return $category->members();
    }

    /**
     * Get API response from a files-info request
     *
     * @return array
     */
    private function getInfoResponse()
    {
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * Set API parameters for an imageinfo query
     *
     * @see https://www.mediawiki.org/wiki/API:Imageinfo
     * @return void
     */
    private function setImageinfoParams()
    {
        $this->setParam('prop', 'imageinfo');
        $this->setParam('iiprop', 'url|size|mime|thumbmime|user|userid|sha1|timestamp|extmetadata');
        $this->setParam('iiextmetadatafilter', 'LicenseShortName|UsageTerms|AttributionRequired|'
                        .'Restrictions|Artist|ImageDescription|DateTimeOriginal');
        $this->setParam('iiurlwidth', $this->width);
    }
}
