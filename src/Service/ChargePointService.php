<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChargePointService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $id
     * @return Array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getChargePoint($id): Array
    {
        $data = null;
        $response = null;
        $url = 'http://localhost:8000/api/chargepoint/' . $id;

        $response = $this->httpClient->request('GET', $url);

        $data = $response->getContent();

        return json_decode($data, true);
    }

    /**
     * @return Array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getChargePoints(): Array
    {
        $data = null;
        $response = null;
        $url = 'http://localhost:8000/api/chargepoint/all';

        $response = $this->httpClient->request('GET', $url);

        $data = $response->getContent();

        return json_decode($data, true);
    }
}