<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Sources;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Attogram SharedMedia API
 */
class Api
{
    const VERSION = '0.9.13';

    const DEFAULT_LIMIT = 50;

    public $logger;

    private $endpoint;
    private $client;
    private $params = [];
    private $request;
    private $response;
    private $limit;

    /**
     * @return void
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
    }

    /**
     * Set a PSR3 logger
     *
     * @param mixed $log
     * @return void
     */
    private function setLogger(LoggerInterface $logger = null)
    {
        if ($logger instanceof LoggerInterface) {
            $this->logger = $logger;
            return;
        }
        $this->logger = new Logger('Log');
        $this->logger->pushHandler(new StreamHandler('php://output'));
    }

    /**
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->logger->debug('Api::setEndpoint:', [$endpoint]);
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        if (!is_string($this->endpoint) || !$this->endpoint) {
            $this->setEndpoint(Sources::getSource());
        }
        return $this->endpoint;
    }

    /**
     * @return void
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        $this->logger->debug('Api::setLimit:', [$limit]);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        if (!is_numeric($this->limit) || !$this->limit) {
            $this->setLimit(self::DEFAULT_LIMIT);
        }
        return $this->limit;
    }

    /**
     * @return void
     */
    public function setParam($paramName, $paramValue)
    {
        $this->params[$paramName] = $paramValue;
        $this->logger->debug(
            Tools::safeString('Api::setParam: '.$paramName.':'),
            [Tools::safeString($paramValue)]
        );
    }

    /**
     * @return bool
     */
    private function hasParams()
    {
        if (!$this->params || !is_array($this->params)) {
            $this->logger->error('Api::hasParams: params Not Found');
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function send()
    {
        if (!$this->hasParams()) {
            $this->logger->error('Api::send: params Not Found');
            return false;
        }
        $this->setParam('action', 'query');
        $this->setParam('format', 'json');
        $this->setParam('formatversion', 2);
        $this->logger->info('Api::send: <a target="commons" href="'.$this->getUrl().'">'.$this->getUrl().'</a>');
        try {
            $this->request = $this->getClient()->request(
                'GET',
                $this->getEndpoint(),
                ['query' => $this->params]
            );
        } catch (ConnectException $exception) {
            $this->logger->error('Api::send: ConnectException: '.$exception->getMessage());
            return false;
        }
        $this->logger->info('Api::send: '.$this->request->getStatusCode().': '.$this->request->getReasonPhrase());
        if (!$this->decodeRequest()) {
            $this->logger->error('Api::send: decode failed');
            return false;
        };
        return true;
    }

    /**
     * @return bool
     */
    private function decodeRequest()
    {
        if ($this->response = json_decode($this->request->getBody(), /*assoc array*/true)) {
            return true;
        }
        return false;
    }

    /**
     * @return array|mixed
     */
    public function getResponse($keys = null)
    {
        $this->logger->debug('Api::getResponse', [$this->response]);
        if (!$this->response) {
            $this->logger->error('Api::getResponse: No Response Found');
            return [];
        }
        if (!is_array($this->response)) {
            $this->logger->error('Api::getResponse: Response Not Array');
            return [$this->response];
        }
        $response = $this->getResponseFromKeys($keys);
        $this->logger->info('Api::getResponse: count: '.count($response));
        return $response;
    }

    public function getResponseFromKeys($keys)
    {
        if (!$keys) {
            $this->logger->debug('Api::getResponseFromKeys: Keys Not Found.');
            return $this->response;
        }
        if (!is_array($keys)) {
            $this->logger->debug('Api::getResponseFromKeys: Keys Not Array.');
            return $this->response;
        }
        $found = $this->response;
        foreach ($keys as $key) {
            if (!isset($found[$key])) {
                $this->logger->error('Api::getResponseFromKeys: Key Not Found: '.$key);
                return $this->response;
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
        return $this->getEndpoint().'?'.http_build_query($this->params);
    }

    /**
     * @return object \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client ?: new Client();
    }
}
