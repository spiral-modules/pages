<?php

namespace Spiral\Tests;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\ServerRequest;

abstract class HttpTest extends BaseTest
{
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

//        $this->app->getBootloader()->bootload([
//            Can speed up class loading a bit
//            \Spiral\Core\Loader::class,
//
            //Short bindings in spiral services (eg http, db, ...)
//            \Spiral\Core\Bootloaders\SpiralBindings::class,

            //Vault booltloader
//            \Spiral\Vault\Bootloaders\VaultBootloader::class,
//            \Spiral\Vault\Bootloaders\InsecureBootloader::class
//        ]);
    }

    /**
     * Execute GET query.
     *
     * @param string|UriInterface $uri
     * @param array               $query
     * @param array               $headers
     * @param array               $cookies
     *
     * @return ResponseInterface
     */
    protected function get(
        $uri,
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface
    {
        return $this->app->http->perform(
            $this->createRequest($uri, 'GET', $query, $headers, $cookies)
        );
    }

    /**
     * Execute POST query.
     *
     * @param string|UriInterface $uri
     * @param array               $data
     * @param array               $headers
     * @param array               $cookies
     *
     * @return ResponseInterface
     */
    protected function post(
        $uri,
        array $data = [],
        array $headers = [],
        array $cookies = []
    ): ResponseInterface
    {
        return $this->app->http->perform(
            $this->createRequest($uri, 'POST', [], $headers, $cookies)->withParsedBody($data)
        );
    }

    /**
     * @param string|UriInterface $uri
     * @param string              $method
     * @param array               $query
     * @param array               $headers
     * @param array               $cookies
     *
     * @return ServerRequest
     */
    protected function createRequest(
        $uri,
        string $method = 'GET',
        array $query = [],
        array $headers = [],
        array $cookies = []
    ): ServerRequest
    {
        return new ServerRequest([], [], $uri, $method, 'php://input', $headers, $cookies, $query);
    }

    /**
     * Fetch array of cookies from response.
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function fetchCookies(ResponseInterface $response)
    {
        $result = [];
        foreach ($response->getHeader('Set-Cookie') as $line) {
            $cookie = explode('=', $line);
            $result[$cookie[0]] = rawurldecode(substr($cookie[1], 0, strpos($cookie[1], ';')));
        }

        return $result;
    }

}