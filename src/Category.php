<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Category object
 */
class Category extends Base
{
    const VERSION = '0.10.4';

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

	/**
	 * format a category response as a simple string
	 *
	 * @param array $response
	 * @return string
	 */
	public function format(array $response)
	{
		$cr = '<br />';
		$format = '';
		foreach ($response as $category) {
			$format .= '<div class="category">'
			. '<span class="title">' . Tools::getFromArray($category, 'title') . '</span>'
			.$cr.'pageid: ' . '<span class="pageid">' . Tools::getFromArray($category, 'pageid') . '</span>'
			.$cr.'files: ' . Tools::getFromArray($category, 'files')
			.$cr.'pages: ' . Tools::getFromArray($category, 'pages')
			.$cr.'subcats: ' . Tools::getFromArray($category, 'subcats')
			.$cr.'size: ' . Tools::getFromArray($category, 'size')
			.$cr.'hidden: ' . (isset($category['hidden']) && $category['hidden'] ? 'true' : 'false')
			.'</div>';
		}
		return $format;
	}
}
