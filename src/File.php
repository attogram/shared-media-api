<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * File object
 * Attogram SharedMedia API
 */
class File extends Api
{
    const VERSION = '0.9.6';

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
        $this->logger->debug('File::search: query: '.$query);
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
        return $this->getInfoResponse();
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


    /**
     * get File information of Files embedded on a Page
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @return array
     */
    public function on()
    {
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        return $this->getInfoResponse();
    }

}
