<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Category object
 */
class Category extends Base
{
    const VERSION = '0.9.9';

    /**
     * search for categories
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        $this->logger->debug('Category::search');
        if (!Tools::isGoodString($query)) {
            $this->logger->error('Category::search: invalid query');
            return [];
        }
        $this->setParam('generator', 'search');
        $this->setParam('gsrnamespace', self::CATEGORY_NAMESPACE);
        $this->setParam('gsrlimit', $this->getLimit());
        $this->setParam('gsrsearch', $query);
        return $this->getCategoryinfoResponse();
    }

    /**
     * get category information
     *
     * @return array
     */
    public function info()
    {
        $this->logger->debug('Category::info');
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        return $this->getCategoryinfoResponse();
    }

    /**
     * get categories from a page
     *
     * @see https://www.mediawiki.org/wiki/API:Categories
     * @return array
     */
    public function fromPage()
    {
        $this->logger->debug('Category::fromPage');
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setParam('generator', 'categories');
        $this->setParam('clprop', 'hidden|timestamp');
        $this->setParam('cllimit', $this->getLimit());
        return $this->getCategoryinfoResponse();
    }

    /**
     * get a list of subcategories of a category
     *
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     * @return array
     */
    public function subcats()
    {
        $this->logger->debug('Category::subcats');
        if (!$this->setIdentifier('gcm', '')) {
            return [];
        }
        $this->setParam('generator', 'categorymembers');
        $this->setParam('gcmprop', 'ids|title');
        $this->setParam('gcmlimit', $this->getLimit());
        $this->setParam('cmtype', 'subcat');
        return $this->getCategoryinfoResponse();
    }

    /**
     * get a list of files in a category
     *
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     * @return array
     */
    public function members()
    {
        $this->logger->debug('Category::members');
        if (!$this->setIdentifier('gcm', '')) {
            return [];
        }
        $this->setParam('generator', 'categorymembers');
        $this->setParam('gcmprop', 'ids|title');
        $this->setParam('gcmlimit', $this->getLimit());
        $this->setParam('cmtype', 'file');
        return $this->getCategoryinfoResponse();
    }

    /**
     * @return array
     */
    private function getCategoryinfoResponse()
    {
        $this->logger->debug('Category::getCategoryinfoResponse');
        $this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
