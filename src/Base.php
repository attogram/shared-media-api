<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Tools;

/**
 * Base Object
 */
class Base extends Transport
{
	const VERSION = '0.10.4';

	const DEFAULT_LIMIT = 10;

	const CATEGORY_NAMESPACE = 14;
	const MEDIA_NAMESPACE = 6;
	const PAGE_NAMESPACE = 0;

	public $thumbnailWidth = 125;

	private $pageid;
	private $title;
	private $limit;

	/**
	 * @param int|null $pageid
	 */
	public function setPageid($pageid = null)
	{
		$this->logger->debug('Base:setPageid', [$pageid]);
		$this->pageid = $pageid;
	}

	/**
	 * @param string|null $title
	 */
	public function setTitle($title = null)
	{
		$this->logger->debug('Base:setTitle', [$title]);
		$this->title = $title;
	}

	/**
	 * @param string|null $prefix
	 * @param string|null $postfix
	 * @return bool
	 */
	public function setIdentifier($prefix = '', $postfix = '')
	{
		if (!$this->pageid && !$this->title) {
			$this->logger->error('Base::setIdentifier: Identifier Not Found');
			return false;
		}
		if ($this->pageid) {
			return $this->setIdentifierValue('pageid', $prefix, $postfix);
		}
		return $this->setIdentifierValue('title', $prefix, $postfix);
	}

	/**
	 * @param string $type 'pageid' or 'title'
	 * @param string|null $prefix
	 * @param string|null $postfix
	 * @return bool
	 */
	private function setIdentifierValue($type, $prefix = '', $postfix = '')
	{
		if (!in_array($type, ['pageid', 'title'])) {
			$this->logger->error('Base::setIdentifierValue: invalid type');
			return false;
		}
		$this->setParam($prefix.$type.$postfix, Tools::valuesImplode($this->{$type}));
		return true;
	}

	/**
	 * @return void
	 */
	public function setLimit($limit)
	{
		$this->logger->debug('Base:setLimit', [$limit]);
		$this->limit = $limit;
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		if (!is_numeric($this->limit) || !$this->limit) {
			$this->setLimit(self::DEFAULT_LIMIT);
		}
		return $this->limit;
	}

	/**
	 * @see https://www.mediawiki.org/wiki/API:Search
	 *
	 * @param string $query
	 * @param int|null $namespace
	 * @return void
	 */
	public function setGeneratorSearch($query, $namespace = null)
	{
		$this->setParam('generator', 'search');
		$this->setParam('gsrlimit', $this->getLimit());
		$this->setParam('gsrsearch', $query);
		if ($namespace) {
			$this->setParam('gsrnamespace', $namespace);
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/API:Categorymembers
	 *
	 * @return void
	 */
	public function setGeneratorCategorymembers()
	{
		$this->setParam('generator', 'categorymembers');
		$this->setParam('gcmprop', 'ids|title|type|timestamp');
		$this->setParam('gcmlimit', $this->getLimit());
	}

	/**
	 * @see https://www.mediawiki.org/wiki/API:Categories
	 *
	 * @return void
	 */
	public function setGeneratorCategories()
	{
		$this->setParam('generator', 'categories');
		$this->setParam('gclprop', 'hidden|timestamp');
		$this->setParam('gcllimit', $this->getLimit());
	}

	/**
	 * @see https://www.mediawiki.org/wiki/API:Images
	 *
	 * @return void
	 */
	public function setGeneratorImages()
	{
		$this->setParam('generator', 'images');
		$this->setParam('gimlimit', $this->getLimit());
	}

	/**
	 * Set API parameters for an imageinfo query
	 *
	 * @see https://www.mediawiki.org/wiki/API:Imageinfo
	 * @return void
	 */
	public function setImageinfoParams()
	{
		$this->setParam('prop', 'imageinfo');
		$this->setParam('iiprop', 'url|size|mime|thumbmime|user|userid|sha1|timestamp|extmetadata');
		$this->setParam('iiextmetadatafilter', 'LicenseShortName|UsageTerms|AttributionRequired|'
						.'Restrictions|Artist|ImageDescription|DateTimeOriginal');
		$this->setParam('iiurlwidth', $this->thumbnailWidth);
	}

	/**
	 * Get API response from a files-info request
	 *
	 * @return array
	 */
	public function getImageinfoResponse()
	{
		$this->setImageinfoParams();
		$this->send();
		return Tools::flatten($this->getResponse(['query', 'pages']));
	}

	/**
	 * @param string $cmtype 'file' or 'subcat'
	 * @return array
	 */
	public function getCategorymemberResponse($cmtype)
	{
		if (!$this->setIdentifier('gcm', '')) {
			return [];
		}
		$this->setGeneratorCategorymembers();
		$this->setParam('gcmtype', $cmtype);
		switch ($cmtype) {
			case 'subcat':
				return $this->getCategoryinfoResponse();
			case 'file':
				return $this->getImageinfoResponse();
		}
	}

	/**
	 * @see https://www.mediawiki.org/wiki/API:Categoryinfo
	 *
	 * @return array
	 */
	public function getCategoryinfoResponse()
	{
		$this->setParam('prop', 'categoryinfo');
		$this->send();
		return Tools::flatten($this->getResponse(['query', 'pages']));
	}
}
