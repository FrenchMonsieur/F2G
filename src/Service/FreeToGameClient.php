<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class FreeToGameClient {

    private $httpClient;

    public function __construct(HttpClientInterface $httpClient) {
        $this->httpClient = $httpClient;
    }

    public function getGames(): array {
        $response = $this->httpClient->request('GET', 'https://www.freetogame.com/api/games');
        return $response->toArray();
    }
}