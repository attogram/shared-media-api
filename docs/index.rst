shared-media-api v0.9
=====================

``shared-media-api`` is a MediaWiki API wrapper that easily gets Category and File
information into a flat PHP array.  Fine-tuned for WikiMedia Commmons.

.. code-block:: php

    // Example: Search categories
    $category = new Category();
    $results = $category->search('Albert Einstein');
    foreach ($results as $result) {
        print_r($result);
    }

.. image:: https://travis-ci.org/attogram/shared-media-api.svg?branch=master
    :target: https://travis-ci.org/attogram/shared-media-api
    :alt: Build Status
.. image:: https://api.codeclimate.com/v1/badges/495c792e36f498fed6ef/maintainability
    :target: https://codeclimate.com/github/attogram/shared-media-api/maintainability
    :alt: Maintainability
.. image:: http://readthedocs.org/projects/shared-media-api/badge/?version=latest
    :target: http://shared-media-api.readthedocs.io/en/latest/?badge=latest
    :alt: Documentation Status

Project links
-------------

* ``shared-media-api`` project home: https://github.com/attogram/shared-media-api
* ``shared-media-api`` documentation: https://shared-media-api.readthedocs.io/en/latest/
* ``shared-media-api`` packagist: https://packagist.org/packages/attogram/shared-media-api
* ``shared-media-api`` travis-ci: https://travis-ci.org/attogram/shared-media-api
* ``shared-media-api`` codeclimate: https://codeclimate.com/github/attogram/shared-media-api

License
-------

* ``shared-media-api`` is Dual Licensed under the MIT or GPL-3.0+, at your choosing.

Contents
--------

.. toctree::
   :hidden:

   About shared-media-api <index>
   Other projects <other>
