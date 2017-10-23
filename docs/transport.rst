Transport
=========

public $logger;
	
public function __construct(LoggerInterface $logger = null)

public function setEndpoint($endpoint)

public function getEndpoint()

public function setParam($paramName, $paramValue)

public function send()

public function getResponse($keys = null)

public function getUrl()
