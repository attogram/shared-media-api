<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Attogram Commons Page
 */
class Page extends Api
{
    const VERSION = '0.9.0';

    const PAGE_NAMESPACE = 0;

    const MAX_LIMIT = 500;


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
            $this->log->error('Page::search: invalid query');
            return array();
        }
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::PAGE_NAMESPACE);
        $this->setParam('gsrlimit', self::MAX_LIMIT);
        $this->setParam('gsrsearch', $query);
        $this->setParam('prop', 'pageprops');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
