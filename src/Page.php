<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Page Object
 */
class Page extends Base
{
    const VERSION = '0.10.2';

    /**
     * search for Pages
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
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
     * format a page response as a simple string
     *
     * @param array $response
     * @return string
     */
    public function format(array $response)
    {
        $car = '<br />';
        $format = '';
        foreach ($response as $page) {
            $format .= '<div class="page">'
            . '<span class="title">'
            . Tools::safeString(Tools::getFromArray($page, 'title'))
            . '</span>'
            .$car.'<span class="pageid">' . Tools::getFromArray($page, 'pageid') . '</span>'
            .$car.Tools::getFromArray($page, 'page_image_free')
            .'</div>';
        }
        return $format;
    }
}
