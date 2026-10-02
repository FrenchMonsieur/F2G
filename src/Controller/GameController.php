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
        $platform = $request->query->get('platform');
        $category = $request->query->get('category');
        $sort = $request->query->get('sort-by');
        if (!in_array($sort, ['popularity', 'release-date', 'alphabetical', 'relevance'])) {
            $sort = null;
        }
        if (!in_array($platform, ['pc', 'browser'])) {
            $platform = null;
        }
        if (!in_array($category,  [
    'mmorpg', 'shooter', 'strategy', 'moba', 'racing', 'sports', 'social',
    'sandbox', 'open-world', 'survival', 'pvp', 'pve', 'pixel', 'voxel',
    'zombie', 'turn-based', 'first-person', 'third-person', 'top-down',
    'tank', 'space', 'sailing', 'side-scroller', 'superhero', 'permadeath',
    'card', 'battle-royale', 'mmo', 'mmofps', 'mmotps', '3d', '2d', 'anime',
    'fantasy', 'sci-fi', 'fighting', 'action-rpg', 'action', 'military',
    'martial-arts', 'flight', 'low-spec', 'tower-defense', 'horror', 'mmorts',
])) {
            $category = null;
        }

        $games = $freeToGameClient->getGames($platform, $category, $sort);

        return $this->render('game/index.html.twig', [
            'games' => $games,
            'platform' => $platform,
            'category' => $category,
            'sort' => $sort,
        ]);
    }
}

