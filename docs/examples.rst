Examples
========

Example: search categories
--------------------------

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


Example: Search for 1 file
--------------------------

.. literalinclude:: examples/example.search.for.1.file.php
   :language: php
