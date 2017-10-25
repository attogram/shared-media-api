<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Category object
 */
class Category extends Base
{
    const VERSION = '0.10.1';

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
        $this->setGeneratorSearch($query, self::CATEGORY_NAMESPACE);
        return $this->getCategoryinfoResponse();
    }

    /**
     * get category information
     *
     * @return array
     */
    public function info()
    {
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
    public function getCategoryfromPage()
    {
        if (!$this->setIdentifier('', 's')) {
            return [];
        }
        $this->setGeneratorCategories();
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
        return $this->getCategorymemberResponse('subcat');
    }
}
