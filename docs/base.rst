Base object
===========

Extended by Category, Media, and Page objects.

public function setPageid($pageid = null)

public function setTitle($title = null)

public function setIdentifier($prefix = '', $postfix = '')

public function setResponseLimit($limit)

public function getLimit()


Transport object
================

Extended by Base object

public function setLogger(LoggerInterface $logger = null)

public function setEndpoint($endpoint)

public function getEndpoint()

public function setParam($paramName, $paramValue)

public function send()

public function getResponse($keys = null)

public function getUrl()
	