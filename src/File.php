<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * File object
 * Attogram SharedMedia API
 */
class File extends Api
{
    const VERSION = '0.9.5';

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
     * get File information from a pageid or list of pageids
     *
     * @param array|string $pageids
     * @return array
     */
    public function infoPageid($pageids)
    {
        if (empty($pageids)) {
            $this->logger->error('File::infoPageid: invalid pageids');
            return [];
        }
        $pageids = Tools::valuesImplode($pageids);
        $this->logger->debug('File::infoPageid: pageids: '.$pageids);
        $this->setParam('pageids', $pageids);
        return $this->getInfoResponse();
    }

    /**
     * get File information from a title or list of titles
     *
     * @param array|string $titles
     * @return array
     */
    public function infoTitle($titles)
    {
        if (empty($titles)) {
            $this->logger->error('File::infoTitle: invalid titles');
            return [];
        }
        $titles = Tools::valuesImplode($titles);
        $this->logger->debug('File::infoTitle: titles: '.$titles);
        $this->setParam('titles', $titles);
        return $this->getInfoResponse();
    }

    /**
     * get File information of Files embedded on a Page, from a pageid or list of pageids
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @param array|string $pageids
     * @return array
     */
    public function onPageid($pageids)
    {
        $pageids = Tools::valuesImplode($pageids);
        $this->logger->debug('File::embeddedOnPageid: pageids: '.$pageids);
        $this->setParam('pageids', $pageids);
        return $this->getOnResponse();
    }

    /**
     * get File information of Files embedded on a Page, from a title or list of titles
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @param array|string $titles
     * @return array
     */
    public function onTitle($titles)
    {
        $titles = Tools::valuesImplode($titles);
        $this->logger->debug('File::embeddedOnPageid: titles: '.$titles);
        $this->setParam('titles', $titles);
        return $this->getOnResponse();
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
     * Get API response from a files-embedded-on-page request
     * @return array
     */
    private function getOnResponse()
    {
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        return $this->getInfoResponse();
    }
}
