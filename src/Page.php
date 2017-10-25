<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Page Object
 */
class Page extends Base
{
    const VERSION = '0.10.0';

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
}
