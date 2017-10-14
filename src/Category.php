<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Attogram Commons Category
 */
class Category extends Api
{
    const VERSION = '0.9.4';

    const CATEGORY_NAMESPACE = 14;

    /**
     * search for categories
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        if (!Tools::isGoodString($query)) {
            $this->logger->error('Category::search: invalid query');
            return [];
        }
        $this->logger->debug('Category::search: query: '.$query);
        $this->setParam('list', 'search');
        $this->setParam('srnamespace', self::CATEGORY_NAMESPACE);
        $this->setParam('srprop', 'size|snippet|timestamp'); // titlesnippet|title
        $this->setParam('srlimit', $this->getLimit());
        $this->setParam('srsearch', $query);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'search']));
    }

    /**
     * get a list of files in a category
     *
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     * @param string $categoryTitle
     * @return array
     */
    public function members($categoryTitle)
    {
        if (!Tools::isGoodString($categoryTitle)) {
            $this->logger->error('Category::members: invalid categoryTitle');
            return [];
        }
        $this->logger->debug('Category::members: categoryTitle: '.$categoryTitle);
        $this->setParam('list', 'categorymembers');
        $this->setParam('cmtype', 'file');
        $this->setParam('cmprop', 'ids|title');
        $this->setParam('cmlimit', $this->getLimit());
        $this->setParam('cmtitle', $categoryTitle); // cmtitle OR cmpageid
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'categorymembers']));
    }

    /**
     * get categories from a pageid
     *
     * @see https://www.mediawiki.org/wiki/API:Categories
     * @param int $pageid
     * @return array
     */
    public function from($pageid)
    {
        if (!is_numeric($pageid)) {
            $this->logger->error('Category::from: invalid pageid');
            return [];
        }
        $this->logger->debug('Category::from: pageid: '.$pageid);
        $this->setParam('prop', 'categories');
        $this->setParam('clprop', 'hidden'); // timestamp|hidden
        $this->setParam('cllimit', $this->getLimit());
        $this->setParam('pageids', $pageid);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages', $pageid, 'categories']));
    }

    /**
     * get category information
     *
     * @param string|array $categoryTitle
     * @return array
     */
    public function info($categoryTitle)
    {
        if (!Tools::isGoodString($categoryTitle)) {
            $this->logger->error('Category::info: invalid categoryTitle');
            return [];
        }
        $this->logger->debug('Category::info: categoryTitle: '.$categoryTitle);
        $this->setParam('prop', 'categoryinfo');
        $this->setParam('titles', $categoryTitle);
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'pages']));
    }

    /**
     * get a list of subcategories of a category
     *
     * @see https://www.mediawiki.org/wiki/API:Categorymembers
     * @param string $categoryTitle
     * @return array
     */
    public function subcats($categoryTitle)
    {
        if (!Tools::isGoodString($categoryTitle)) {
            $this->logger->error('Category::subcats: invalid categoryTitle');
            return [];
        }
        $this->logger->debug('Category::subcats: categoryTitle: '.$categoryTitle);
        $this->setParam('list', 'categorymembers');
        $this->setParam('cmtype', 'subcat');
        $this->setParam('cmprop', 'ids|title');
        $this->setParam('cmlimit', $this->getLimit());
        $this->setParam('cmtitle', $categoryTitle); // cmtitle OR cmpageid
        $this->send();
        return Tools::flatten($this->getResponse(['query', 'categorymembers']));
    }
}
