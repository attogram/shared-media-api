shared-media-api v0.9
=====================

``shared-media-api`` is a MediaWiki API wrapper that easily gets Category and File
information into a flat PHP array.  Fine-tuned for WikiMedia Commmons.

Example: search categories:

.. code-block:: php

	use Attogram\SharedMedia\Api\Category;

    $category = new Category();
    $category->setLimit(2);
    $results = $category->search('Albert Einstein');
    foreach ($results as $result) {
        print_r($result);
    }

Result:

.. code-block:: none

    Array
    (
        [pageid] => 970886
        [ns] => 14
        [title] => Category:Albert Einstein
        [index] => 1
        [categoryinfo.size] => 198
        [categoryinfo.pages] => 3
        [categoryinfo.files] => 177
        [categoryinfo.subcats] => 18
        [categoryinfo.hidden] =>
    )
    Array
    (
        [pageid] => 4975712
        [ns] => 14
        [title] => Category:Einstein-Szilárd letter
        [index] => 7
        [categoryinfo.size] => 3
        [categoryinfo.pages] => 0
        [categoryinfo.files] => 3
        [categoryinfo.subcats] => 0
        [categoryinfo.hidden] =>
    )

.. image:: https://travis-ci.org/attogram/shared-media-api.svg?branch=master
    :target: https://travis-ci.org/attogram/shared-media-api
    :alt: Build Status
.. image:: https://api.codeclimate.com/v1/badges/495c792e36f498fed6ef/maintainability
    :target: https://codeclimate.com/github/attogram/shared-media-api/maintainability
    :alt: Maintainability
.. image:: http://readthedocs.org/projects/shared-media-api/badge/?version=latest
    :target: http://shared-media-api.readthedocs.io/en/latest/?badge=latest
    :alt: Documentation Status


.. toctree::

    about
	examples
    category
    file
    page
    base
    api
    sandbox
    tools
    sources
    other
