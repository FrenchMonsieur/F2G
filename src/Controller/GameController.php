<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FreeToGameClient;
use Symfony\Component\HttpFoundation\Request;
final class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(FreeToGameClient $freeToGameClient, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $platform = $request->query->get('platform');
        $category = $request->query->get('category');
        $sort = $request->query->get('sort-by');
        if ($page < 1) {
            $page = 1;
        }
        if (!in_array($sort, ['popularity', 'release-date', 'alphabetical', 'relevance'])) {
            $sort = null;
        }
        if (!in_array($platform, ['pc', 'browser'])) {
            $platform = null;
        }
        if (
            !in_array($category, [
                'mmorpg',
                'shooter',
                'strategy',
                'moba',
                'racing',
                'sports',
                'social',
                'sandbox',
                'open-world',
                'survival',
                'pvp',
                'pve',
                'pixel',
                'voxel',
                'zombie',
                'turn-based',
                'first-person',
                'third-person',
                'top-down',
                'tank',
                'space',
                'sailing',
                'side-scroller',
                'superhero',
                'permadeath',
                'card',
                'battle-royale',
                'mmo',
                'mmofps',
                'mmotps',
                '3d',
                '2d',
                'anime',
                'fantasy',
                'sci-fi',
                'fighting',
                'action-rpg',
                'action',
                'military',
                'martial-arts',
                'flight',
                'low-spec',
                'tower-defense',
                'horror',
                'mmorts',
            ])
        ) {
            $category = null;
        }

        // 1. On récupère TOUS les jeux (le paquet entier)
        $games = $freeToGameClient->getGames($platform, $category, $sort);

        // 2. On compte le paquet entier et on calcule le nombre de pages
        $totalPages = (int) ceil(count($games) / 24);

        // 3. Si la page demandée est trop grande, on va à la dernière
        if ($totalPages > 0 && $page > $totalPages) {
            $page = $totalPages;
        }

        // 4. Seulement maintenant, on découpe les 24 jeux de la page
        $games = array_slice($games, ($page - 1) * 24, 24);

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'platform' => $platform,
            'category' => $category,
            'sort' => $sort,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
    #[Route('/game/{id}', name: 'app_game_detail')]
    public function detail(FreeToGameClient $freeToGameClient, int $id): Response
    {
        $game = $freeToGameClient->getGame($id);
        try {
            if (!$game) {
                throw $this->createNotFoundException('Game not found');
            }
        } catch (\Exception $e) {
            throw $this->createNotFoundException('Game not found');
        }
        return $this->render('game/detail.html.twig', [
            'game' => $game,
        ]);
    }
}

