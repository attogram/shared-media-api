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
    const VERSION = '0.9.3';

    public $log;
    private $endpoint;
    private $client;
    private $params = array();
    private $request;
    private $response;

    /**
     * @return void
     */
    public function __construct(LoggerInterface $log = null)
    {
        $this->setLogger($log);
    }

    /**
     * Set a PSR3 logger
     *
     * @uses Api::$log
     * @param mixed $log
     * @return void
     */
    private function setLogger(LoggerInterface $log = null)
    {
        if ($log instanceof LoggerInterface) {
            $this->log = $log;
            return;
        }
        $this->log = new Logger('Log');
        $this->log->pushHandler(new StreamHandler('php://output'));
    }

    /**
     * @uses Api::$endpoint
     * @return void
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        $this->log->debug('Api::setEndpoint: '.$endpoint);
    }

    /**
     * @uses Api::$endpoint
     * @return string
     */
    public function getEndpoint()
    {
        if (!is_string($this->endpoint) || !$this->endpoint) {
            //$this->setEndpoint('https://commons.wikimedia.org/w/api.php');
            $this->setEndpoint(Sources::getSource());
        }
        return $this->endpoint;
    }

    /**
     * @uses Api::$param
     * @return void
     */
    public function setParam($paramName, $paramValue)
    {
        $this->params[$paramName] = $paramValue;
        $this->log->debug('Api::setParam: '.$paramName.' = '.$paramValue);
    }

    /**
     * @uses Api::$request
     * @uses Api::$endpoint
     * @uses Api::$params
     * @return bool
     */
    public function send()
    {
        if (!$this->params || !is_array($this->params)) {
            $this->log->error('Api::send: params is empty');
            return false;
        }
        $this->setParam('action', 'query');
        $this->setParam('format', 'json');
        $this->setParam('formatversion', 2);
        try {
            $this->request = $this->getClient()->request(
                'GET',
                $this->getEndpoint(),
                ['query' => $this->params]
            );
        } catch (ConnectException $exception) {
            $this->log->error('Api::send: ConnectException: '.$exception->getMessage());
            return false;
        }
        $this->log->debug('Api::send: '.$this->request->getStatusCode().': '.$this->request->getReasonPhrase());
        if (!$this->decodeRequest()) {
            $this->log->error('Api::send: decode failed');
            return false;
        };
        return true;
    }

    /**
     * @uses Api::$response
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
     * @uses Api::$response
     * @return array|mixed
     */
    public function getResponse($keys = null)
    {
        if (!is_array($this->response) || !$this->response) {
            $this->log->error('Api::getResponse: No Response Found');
            return [];
        }
        if (!is_array($keys) || !$keys) {
            $this->log->debug('Api::getResponse: Full Response returned');
            return $this->response;
        }
        $found = $this->response;
        foreach ($keys as $key) {
            if (!isset($found[$key])) {
                $this->log->error('Api::getResponse: Key Not Found: '.$key);
                return [];
            }
            $found = $found[$key];
        }
        $this->log->debug('Api::getResponse: Response size: '.count($found));
        return $found;
    }

    /**
     * @uses Api::$response
     * @return bool
     */
    public function isBatchcomplete()
    {
        return isset($this->response['batchcomplete'])
            ? true : false;
    }

    /**
     * @uses Api::$client
     * @return object \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client ?: new Client();
    }

    /**
     * @uses Api::$response
     * @return string|false
     */
    public function getTotalhits()
    {
        return isset($this->response['query']['searchinfo']['totalhits'])
            ? $this->response['query']['searchinfo']['totalhits'] : false;
    }

    /**
     * @uses Api::$response
     * @return mixed
     */
    public function getWarnings()
    {
        return isset($this->response['warnings'])
            ? $this->response['warnings'] : false;
    }

    /**
     * @uses Api::$response
     * @return string|false
     */
    public function getContinue()
    {
        return isset($this->response['continue']['continue'])
            ? $this->response['continue']['continue'] : false;
    }

    /**
     * @uses Api::$response
     * @return int|false
     */
    public function getSroffset()
    {
        return isset($this->response['continue']['sroffset'])
            ? $this->response['continue']['sroffset'] : false;
    }
}
