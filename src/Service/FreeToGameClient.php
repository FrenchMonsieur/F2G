<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class FreeToGameClient
{
    private $httpClient;
    private $cache;

    public function __construct(HttpClientInterface $httpClient, CacheInterface $cache)
    {
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getGames(?string $platform = null, ?string $category = null, ?string $sort = null): array
    {
        $cleCache = 'games_' . ($platform ?? 'all') . '_' . ($category ?? 'all') . '_' . ($sort ?? 'default');
        return $this->cache->get($cleCache, function ($item) use ($platform, $category, $sort) {
            $item->expiresAfter(86400);
            $queryParams = array_filter(['platform' => $platform, 'category' => $category, 'sort-by' => $sort]);
            $response = $this->httpClient->request('GET', 'https://www.freetogame.com/api/games', [
                'query' => $queryParams,
            ]);
            return $response->toArray();
        });
    }

    public function getGame(int $id): ?array
    {
        $cleCache = 'game_' . $id;
        return $this->cache->get($cleCache, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(86400);
            try {
                $response = $this->httpClient->request('GET', 'https://www.freetogame.com/api/game', [
                    'query' => ['id' => $id],
                ]);
                return $response->toArray();
            } catch (\Exception $e) {
                return null;
            }
        });
    }
}