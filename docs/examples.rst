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

.. code-block:: php

    use Attogram\SharedMedia\Api\File;

    $file = new File();
    $file->setLimit(1);
    $result = $file->search('Albert Einstein');
    print_r($result);

Result:

.. code-block:: none

    Array
    (
        [0] => Array
            (
                [pageid] => 925243
                [ns] => 6
                [title] => File:Albert Einstein Head.jpg
                [index] => 1
                [imagerepository] => local
                [imageinfo.0.timestamp] => 2014-11-25T19:59:28Z
                [imageinfo.0.user] => Triggerhippie4
                [imageinfo.0.userid] => 1821096
                [imageinfo.0.size] => 2309396
                [imageinfo.0.width] => 3250
                [imageinfo.0.height] => 4333
                [imageinfo.0.thumburl] => https://upload.wikimedia.org/wikipedia/commons/thumb/d/d3/Albert_Einstein_Head.jpg/100px-Albert_Einstein_Head.jpg
                [imageinfo.0.thumbwidth] => 100
                [imageinfo.0.thumbheight] => 133
                [imageinfo.0.thumbmime] => image/jpeg
                [imageinfo.0.url] => https://upload.wikimedia.org/wikipedia/commons/d/d3/Albert_Einstein_Head.jpg
                [imageinfo.0.descriptionurl] => https://commons.wikimedia.org/wiki/File:Albert_Einstein_Head.jpg
                [imageinfo.0.descriptionshorturl] => https://commons.wikimedia.org/w/index.php?curid=925243
                [imageinfo.0.sha1] => 51f46ff9897d9125b0d0a513fb5099d2a9462282
                [imageinfo.0.extmetadata.ImageDescription.value] => <a href="https://en.wikipedia.org/wiki/Albert_Einstein" class="extiw" title="en:Albert Einstein">Albert Einstein</a>
                [imageinfo.0.extmetadata.ImageDescription.source] => commons-desc-page
                [imageinfo.0.extmetadata.DateTimeOriginal.value] => Copyrighted 1947, copyright not renewed. Einstein's estate may still claim copyright on this image, but any such claim would be considered illegitimate by the Library of Congress. No known restrictions.<a rel="nofollow" class="external autonumber" href="http://www.loc.gov/pictures/item/2004671908/">[1]</a>
                [imageinfo.0.extmetadata.DateTimeOriginal.source] => commons-desc-page
                [imageinfo.0.extmetadata.Artist.value] => Photograph by <a href="//commons.wikimedia.org/w/index.php?title=Orren_Jack_Turner&amp;action=edit&amp;redlink=1" class="new" title="Orren Jack Turner (page does not exist)">Orren Jack Turner</a>, Princeton, N.J. <br>Modified with Photoshop by <a href="https://en.wikipedia.org/wiki/User:PM_Poon" class="extiw" title="en:User:PM Poon">PM_Poon</a> and later by <a href="//commons.wikimedia.org/wiki/User:Dantadd" title="User:Dantadd">Dantadd</a>.
                [imageinfo.0.extmetadata.Artist.source] => commons-desc-page
                [imageinfo.0.extmetadata.LicenseShortName.value] => Public domain
                [imageinfo.0.extmetadata.LicenseShortName.source] => commons-desc-page
                [imageinfo.0.extmetadata.LicenseShortName.hidden] =>
                [imageinfo.0.extmetadata.UsageTerms.value] => Public domain
                [imageinfo.0.extmetadata.UsageTerms.source] => commons-desc-page
                [imageinfo.0.extmetadata.UsageTerms.hidden] =>
                [imageinfo.0.extmetadata.AttributionRequired.value] => false
                [imageinfo.0.extmetadata.AttributionRequired.source] => commons-desc-page
                [imageinfo.0.extmetadata.AttributionRequired.hidden] =>
                [imageinfo.0.extmetadata.Restrictions.value] =>
                [imageinfo.0.extmetadata.Restrictions.source] => commons-desc-page
                [imageinfo.0.extmetadata.Restrictions.hidden] =>
                [imageinfo.0.mime] => image/jpeg
            )


