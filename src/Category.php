<?php

namespace Attogram\SharedMedia\Api;

/**
 * Category object
 */
class Category extends Base
{
    const VERSION = '1.0.3';

    /**
     * search for categories
     *
     * @see https://www.mediawiki.org/wiki/API:Search
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        $this->logger->debug('Category:search:', [$query]);
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
        $this->logger->debug('Category:info');
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
        $this->logger->debug('Category:getCategoryfromPage');
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
        $this->logger->debug('Category:subcats');
        return $this->getCategorymemberResponse('subcat');
    }

    /**
     * format a category response as an HTML string
     *
     * @param array $response
     * @return string
     */
    public function format(array $response)
    {
        $this->logger->debug('Category:format');
        $car = '<br />';
        $format = '';
        foreach ($response as $category) {
            $format .= '<div class="category">'
            . '<span class="title">'
            . Tools::safeString(Tools::getFromArray($category, 'title'))
            . '</span>'
            . $car . 'pageid: ' . '<span class="pageid">' . Tools::getFromArray($category, 'pageid') . '</span>'
            . $car . 'files: ' . Tools::getFromArray($category, 'files')
            . $car . 'pages: ' . Tools::getFromArray($category, 'pages')
            . $car . 'subcats: ' . Tools::getFromArray($category, 'subcats')
            . $car . 'size: ' . Tools::getFromArray($category, 'size')
            . $car . 'hidden: ' . (isset($category['hidden']) && $category['hidden'] ? 'true' : 'false')
            .'</div>';
        }
        return $format;
    }
}
