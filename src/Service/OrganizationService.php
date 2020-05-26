<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrganizationService
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
    public function getOrganization($id): Array
    {
        $data = null;
        $response = null;
        $url = 'http://localhost:8000/api/organization/get' . $id;

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
    public function getOrganizations(): Array
    {
        $data = null;
        $response = null;
        $url = 'http://localhost:8000/api/organization/all';

        $response = $this->httpClient->request('GET', $url);
        $data = $response->getContent();

        return json_decode($data, true);
    }

}