<?php

namespace Attogram\SharedMedia\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Attogram SharedMedia Api Transport
 */
class Transport implements LoggerAwareInterface
{
    const VERSION = '1.0.4';

    /** @var LoggerInterface $logger */
    public $logger;

    /** @var string $endpoint  */
    private $endpoint;
    /** @var GuzzleClient $client */
    private $client;
    /** @var array $params */
    private $params = [];
    /** @var \GuzzleHttp\Psr7\Request $request */
    private $request;
    /** @var array $response */
    private $response;

    /**
     * @param LoggerInterface|null $logger - PSR3 logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
    }

    /**
     * Set a PSR3 logger, or the NullLogger by default
     *
     * @param LoggerInterface|null $logger - PSR3 logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        if ($logger instanceof LoggerInterface) {
            $this->logger = $logger;
            return;
        }
        $this->logger = new NullLogger;
    }

    /**
     * @param $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->logger->debug(
            'Transport:setEndpoint: <a target="commons" href="' . $this->endpoint.'">' . $this->endpoint . '</a>'
        );
    }

    /**
     * @return string
     */
    private function getEndpoint()
    {
        if (!is_string($this->endpoint) || !$this->endpoint) {
            $this->setEndpoint(Sources::getSource());
        }
        return $this->endpoint;
    }

    /**
     * @param string $paramName
     * @param string $paramValue
     */
    protected function setParam($paramName, $paramValue)
    {
        $this->params[$paramName] = $paramValue;
        $this->logger->debug(
            Tools::safeString('Transport:setParam: ' . $paramName . ':'),
            [Tools::safeString($paramValue)]
        );
    }

    /**
     * @return bool
     */
    private function hasParams()
    {
        if (!$this->params || !is_array($this->params)) {
            $this->logger->error('Transport:hasParams: params Not Found');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function send()
    {
        $this->logger->debug('Transport:send');
        if (!$this->hasParams()) {
            $this->logger->error('Transport::send: params Not Found');
            return false;
        }
        $this->setParam('action', 'query');
        $this->setParam('format', 'json');
        $this->setParam('formatversion', 2);
        $this->logger->info(
            'Transport::send: <a target="commons" href="' . $this->getUrl() . '">' . $this->getUrl() . '</a>'
        );
        try {
            $this->request = $this->getClient()->request(
                'GET',
                $this->getEndpoint(),
                ['query' => $this->params]
            );
        } catch (ConnectException $exception) {
            $this->logger->error('Transport::send: ConnectException: '.$exception->getMessage());
            return false;
        }
        $this->logger->info(
            'Transport::send: ' . $this->request->getStatusCode() . ': ' . $this->request->getReasonPhrase()
        );
        if (!$this->decodeRequest()) {
            $this->logger->error('Transport::send: decode failed');
            return false;
        };
        return true;
    }

    /**
     * @return bool
     */
    private function decodeRequest()
    {
        $this->logger->debug('Transport:decodeRequest');
        if (($this->response = json_decode($this->request->getBody(), true))) {
            return true;
        }
        return false;
    }

    /**
     * @param null $keys
     * @return array|\GuzzleHttp\Psr7\Response
     */
    protected function getResponse($keys = null)
    {
        $this->logger->debug('Transport::getResponse', [$this->response]);
        if (!$this->response) {
            $this->logger->error('Transport::getResponse: No Response Found');
            return [];
        }
        if (!is_array($this->response)) {
            $this->logger->error('Transport::getResponse: Response Not Array');
            return [$this->response];
        }
        $response = $this->getResponseFromKeys($keys);
        $this->logger->info('Transport::getResponse: count: ' . count($response));
        return $response;
    }

    /**
     * @param $keys
     * @return array
     */
    private function getResponseFromKeys($keys)
    {
        $this->logger->debug('Transport:getResponseFromKeys:', [$keys]);
        if (!$keys) {
            $this->logger->debug('Transport::getResponseFromKeys: Keys Not Found.');
            return $this->response;
        }
        if (!is_array($keys)) {
            $this->logger->debug('Transport::getResponseFromKeys: Keys Not Array.');
            return $this->response;
        }
        $found = $this->response;
        foreach ($keys as $key) {
            if (!isset($found[$key])) {
                $this->logger->error('Transport::getResponseFromKeys: Key Not Found: ' . $key);
                return [];
            }
            $found = $found[$key];
        }
        return $found;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->getEndpoint() . '?' . http_build_query($this->params);
    }

    /**
     * @return GuzzleClient
     */
    private function getClient()
    {
        $this->logger->debug('Transport:getClient');
        return $this->client ?: new GuzzleClient();
    }
}
