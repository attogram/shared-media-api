<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Attogram Commons File
 */
class File extends Api
{
    const VERSION = '0.9.3';

    const FILE_NAMESPACE = 6;

    public $width = 100;

    private $iiprop = 'url|size|mime|thumbmime|user|userid|sha1|timestamp|extmetadata';

    private $iiextmetadatafilter = 'LicenseShortName|UsageTerms|AttributionRequired|'
        .'Restrictions|Artist|ImageDescription|DateTimeOriginal';

    /**
     * initialize an imageinfo query
     *
     * @see https://www.mediawiki.org/wiki/API:Imageinfo
     * @return void
     */
    private function setImageinfoParams()
    {
        $this->setParam('prop', 'imageinfo');
        $this->setParam('iiprop', $this->iiprop);
        $this->setParam('iiextmetadatafilter', $this->iiextmetadatafilter);
        $this->setParam('iiurlwidth', $this->width);
    }

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
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get information about File(s) via pageid(s)
     *
     * @param array|string $pageids
     * @return array
     */
    public function infoPageid($pageids)
    {
        if (empty($pageids)) {
            $this->logger->error('File::infoFromPageid: invalid pageids');
            return [];
        }
        $pageids = Tools::valuesImplode($pageids);
        $this->logger->debug('File::infoFromPageid: pageids: '.$pageids);
        $this->setImageinfoParams();
        $this->setParam('pageids', $pageids);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get meta information about File(s) via title(s)
     *
     * @param array|string $titles
     * @return array
     */
    public function infoTitle($titles)
    {
        if (empty($titles)) {
            $this->logger->error('File::infoFromTitle: invalid titles');
            return [];
        }
        $titles = Tools::valuesImplode($titles);
        $this->logger->debug('File::infoFromTitle: titles: '.$titles);
        $this->setImageinfoParams();
        $this->setParam('titles', $titles);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get information about Files embedded on a Page, via pageid(s)
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @param array|string $pageids
     * @return array
     */
    public function onPageid($pageids)
    {
        $pageids = Tools::valuesImplode($pageids);
        $this->logger->debug('File::embeddedOnPageid: pageids: '.$pageids);
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        $this->setParam('pageids', $pageids);
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
    /**
     * get information about Files embedded on a Page, via title(s)
     *
     * @see https://www.mediawiki.org/wiki/API:Images
     * @param array|string $titles
     * @return array
     */
    public function onTitle($titles)
    {
        $titles = Tools::valuesImplode($titles);
        $this->logger->debug('File::embeddedOnPageid: titles: '.$titles);
        $this->setParam('generator', 'images');
        $this->setParam('gimlimit', $this->getLimit());
        $this->setParam('titles', $titles);
        $this->setImageinfoParams();
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
