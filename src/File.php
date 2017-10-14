<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Attogram Commons File
 */
class File extends Api
{
    const VERSION = '0.9.2';

    const FILE_NAMESPACE = 6;

    public $width = 100;

    private $iiprop = 'url|size|mime|thumbmime|user|userid|sha1|timestamp|extmetadata';

    private $iiextmetadatafilter = 'LicenseShortName|UsageTerms|AttributionRequired|'
        .'Restrictions|Artist|ImageDescription|DateTimeOriginal';

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
            $this->log->error('File::search: invalid query');
            return array();
        }
        $this->log->debug('File::search: query: '.$query);
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::FILE_NAMESPACE);
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        $this->setParam('prop', 'imageinfo');
        $this->setParam('iiprop', $this->iiprop);
        $this->setParam('iiextmetadatafilter', $this->iiextmetadatafilter);
        $this->setParam('iiurlwidth', $this->width);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get meta information about File(s) via pageid
     *
     * @param array|string $pageids
     * @return array
     */
    public function infoFromPageid($pageids)
    {
        if (!$pageids || empty($pageids)) {
            $this->log->error('File::infoFromPageid: invalid pageids');
            return array();
        }
        if (is_array($pageids)) {
            $pageids = implode('|', $pageids);
        }
        $this->log->debug('File::infoFromPageid: pageids: '.$pageids);
        $this->infoInit();
        $this->setParam('pageids', $pageids);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get meta information about File(s) via title
     *
     * @param array|string $titles
     * @return array
     */
    public function infoFromTitle($titles)
    {
        if (!$titles || empty($titles)) {
            $this->log->error('File::infoFromTitle: invalid titles');
            return array();
        }
        if (is_array($titles)) {
            $titles = implode('|', $titles);
        }
        $this->log->debug('File::infoFromTitle: titles: '.$titles);
        $this->infoInit();
        $this->setParam('titles', $titles);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * initialize an imageinfo query
     * @return void
     */
    private function infoInit()
    {
        $this->setParam('prop', 'imageinfo');
        $this->setParam('iiprop', $this->iiprop);
        $this->setParam('iiextmetadatafilter', $this->iiextmetadatafilter);
        $this->setParam('iiurlwidth', $this->width);
        $this->setParam('iilimit', $this->getLimit());
    }
}
