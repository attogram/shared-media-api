<?php

namespace Attogram\SharedMedia\Api;

use Attogram\SharedMedia\Api\Sources;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Attogram SharedMedia Api Transport
 */
class Transport implements LoggerAwareInterface
{
    const VERSION = '0.10.3';

    public $logger;

    private $endpoint;
    private $client;
    private $params = [];
    private $request;
    private $response;

    /**
     * @param LoggerInterface $logger PSR3 logger
     * @return void
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
    }

    /**
     * Set a PSR3 logger, or the NullLogger by default
     *
     * @param mixed $log
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
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->logger->debug(
            'Transport::setEndpoint: <a target="commons" href="'.$this->endpoint.'">'.$this->endpoint.'</a>'
        );
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
    public function setParam($paramName, $paramValue)
    {
        $this->params[$paramName] = $paramValue;
        $this->logger->debug(
            Tools::safeString('Transport::setParam: '.$paramName.':'),
            [Tools::safeString($paramValue)]
        );
    }

    /**
     * @return bool
     */
    private function hasParams()
    {
        if (!$this->params || !is_array($this->params)) {
            $this->logger->error('Transport::hasParams: params Not Found');
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
            $this->logger->error('Transport::send: params Not Found');
            return false;
        }
        $this->setParam('action', 'query');
        $this->setParam('format', 'json');
        $this->setParam('formatversion', 2);
        $this->logger->info('Transport::send: <a target="commons" href="'.$this->getUrl().'">'.$this->getUrl().'</a>');
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
        $this->logger->info('Transport::send: '.$this->request->getStatusCode().': '.$this->request->getReasonPhrase());
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
        $this->logger->info('Transport::getResponse: count: '.count($response));
        return $response;
    }

    private function getResponseFromKeys($keys)
    {
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
                $this->logger->error('Transport::getResponseFromKeys: Key Not Found: '.$key);
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
        return $this->getEndpoint().'?'.http_build_query($this->params);
    }

    /**
     * @return object \GuzzleHttp\Client
     */
    private function getClient()
    {
        return $this->client ?: new Client();
    }
}
