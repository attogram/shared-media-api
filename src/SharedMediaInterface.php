<?php

namespace Attogram\SharedMedia\Api;

interface SharedMediaInterface
{
    public function setPageid($pageid = null);

    public function setTitle($title = null);

    public function setLimit($limit);

    public function setEndpoint($endpoint);
}
