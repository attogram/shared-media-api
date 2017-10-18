<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Page Object
 */
class Page extends Base
{
    const VERSION = '0.9.5';

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
        $this->logger->debug('Page::search: query: '.$query);
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::PAGE_NAMESPACE);
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        $this->setParam('prop', 'pageprops');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
