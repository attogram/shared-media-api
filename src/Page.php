<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Page Object
 */
class Page extends Base
{
    const VERSION = '1.0.2';

    /**
     * search for Pages
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        $this->logger->debug('Page:search');
        if (!Tools::isGoodString($query)) {
            $this->logger->error('Page::search: invalid query');
            return [];
        }
        $this->setGeneratorSearch($query, self::PAGE_NAMESPACE);
        $this->setParam('prop', 'pageprops');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get page information
     *
     * @return array
     */
    public function info()
    {
        $this->logger->debug('Page:info');
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setParam('prop', 'pageprops');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * format a page response as a simple string
     *
     * @param array $response
     * @return string
     */
    public function format(array $response)
    {
        $this->logger->debug('Page:format');
        $car = '<br />';
        $format = '';
        foreach ($response as $page) {
            $format .= '<div class="page">'
            . '<span class="title">'
            . Tools::safeString(Tools::getFromArray($page, 'title'))
            . '</span>'
            . $car . '<span class="pageid">' . Tools::getFromArray($page, 'pageid') . '</span>'
            . $car . Tools::getFromArray($page, 'page_image_free')
            . $car . Tools::getFromArray($page, 'wikibase_item')
            .'</div>';
        }
        return $format;
    }
}
