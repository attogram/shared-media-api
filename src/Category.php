<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Category object
 */
class Category extends Base
{
    const VERSION = '0.9.7';

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
        $this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
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
        $this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
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
        if (!$this->setIdentifier('cm')) {
            return [];
        }
        $this->setParam('list', 'categorymembers');
        $this->setParam('cmtype', 'subcat');
        $this->setParam('cmprop', 'ids|title');
        $this->setParam('cmlimit', $this->getLimit());
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'categorymembers']));
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
        if (!$this->setIdentifier('cm')) {
            return [];
        }
        $this->setParam('list', 'categorymembers');
        $this->setParam('cmtype', 'file');
        $this->setParam('cmprop', 'ids|title');
        $this->setParam('cmlimit', $this->getLimit());
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'categorymembers']));
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
        $this->setParam('clprop', 'hidden'); // timestamp|hidden
        $this->setParam('cllimit', $this->getLimit());
		$this->setParam('prop', 'categoryinfo');
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }
}
