# shared-media-api v0.9

MediaWiki API wrapper, fine-tuned for WikiMedia Commons. Gets Category and File information into a flat PHP array.

* https://github.com/attogram/shared-media-api

[![Build Status](https://travis-ci.org/attogram/shared-media-api.svg?branch=master)](https://travis-ci.org/attogram/shared-media-api)
[![Maintainability](https://api.codeclimate.com/v1/badges/495c792e36f498fed6ef/maintainability)](https://codeclimate.com/github/attogram/shared-media-api/maintainability)
[![Issue Count](https://codeclimate.com/github/attogram/shared-media-api/badges/issue_count.svg)](https://codeclimate.com/github/attogram/shared-media-api)
[![Latest Stable Version](https://poser.pugx.org/attogram/shared-media-api/v/stable)](https://packagist.org/packages/attogram/shared-media-api)
[![Latest Unstable Version](https://poser.pugx.org/attogram/shared-media-api/v/unstable)](https://packagist.org/packages/attogram/shared-media-api)

# License

* Dual Licensed: MIT or GPL-3.0+, at your choosing

# dev
<pre>
Api::setIdentifier($pageid, $title)	- Set API call identifier as a Pageid, or if no Pageid, then as a Title
Api::setIdentifierPageid($pageid)	- Set API call identifier as a Pageid or a list of Pageids
Api::setIdentifierTitle($title)		- Set API call identifier as a Title or a list of Titles
Category::fromFile()				- Get a list of Categories attached to a File
Category::fromPage()				- Get a list of Categories attached to a Page
Category::info()					- Get Category information
Category::search($query)			- Search for Categories
Category::subcats()					- Get a list of subcategories of a Category
File::inCategory()					- Get a list of Files in a Category
File::info()						- Get information about a File
File::onPage()						- Get a list of Files used on a Page
File::search($query)				- Search for Files
Page::inCategory()					- Get a list of Pages inside a Category
Page::info()						- Get Page information
Page::search($query)				- Search for Pages
Page::usingFile()					- Get a list of Pages where a File is used
</pre>