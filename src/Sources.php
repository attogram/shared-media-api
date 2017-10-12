<?php

namespace Attogram\SharedMedia\Api;

/**
 * SharedMedia Sources
 */
class Sources
{
    const VERSION = '0.9.0';

    public static $sources = [
        'commons'        => 'https://commons.wikimedia.org/w/api.php',
        'en.wikipedia'   => 'https://en.wikipedia.org/w/api.php',
        'de.wikipedia'   => 'https://de.wikipedia.org/w/api.php',
        'fr.wikipedia'   => 'https://fr.wikipedia.org/w/api.php',
        'es.wikipedia'   => 'https://es.wikipedia.org/w/api.php',
        'ja.wikipedia'   => 'https://ja.wikipedia.org/w/api.php',
        'ru.wikipedia'   => 'https://ru.wikipedia.org/w/api.php',
        'it.wikipedia'   => 'https://it.wikipedia.org/w/api.php',
        'pl.wikipedia'   => 'https://pl.wikipedia.org/w/api.php',
        'nl.wikipedia'   => 'https://nl.wikipedia.org/w/api.php',
        'sv.wikipedia'   => 'https://sv.wikipedia.org/w/api.php',
        'vi.wikipedia'   => 'https://vi.wikipedia.org/w/api.php',
        'ceb.wikipedia'  => 'https://ceb.wikipedia.org/w/api.php',
        'war.wikipedia'  => 'https://war.wikipedia.org/w/api.php',
        'zh.wikipedia'   => 'https://zh.wikipedia.org/w/api.php',
        'pt.wikipedia'   => 'https://pt.wikipedia.org/w/api.php',
        'ar.wikipedia'   => 'https://ar.wikipedia.org/w/api.php',
        'fa.wikipedia'   => 'https://fa.wikipedia.org/w/api.php',
        'he.wikipedia'   => 'https://he.wikipedia.org/w/api.php',
        'uk.wikipedia'   => 'https://uk.wikipedia.org/w/api.php',
        'id.wikipedia'   => 'https://id.wikipedia.org/w/api.php',
        'cs.wikipedia'   => 'https://cs.wikipedia.org/w/api.php',
        'ko.wikipedia'   => 'https://ko.wikipedia.org/w/api.php',
        'fi.wikipedia'   => 'https://fi.wikipedia.org/w/api.php',

        'deletionpedia'  => 'http://deletionpedia.org/w/api.php',
        'ecoliwiki'      => 'http://ecoliwiki.net/colipedia/api.php',
        'ganyfd'         => 'http://www.ganfyd.org/api.php',
        'rationalwiki'   => 'https://rationalwiki.org/w/api.php',
        'wikipathways'   => 'http://wikipathways.org/api.php',
    ];

    public static function getSource()
    {
        // return the first source
        return self::$sources[array_keys(self::$sources)[0]];
    }
}
