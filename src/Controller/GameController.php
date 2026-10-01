<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\FreeToGameClient;
final class GameController extends AbstractController
{
    #[Route('/', name: 'app_game')]
    public function index(FreeToGameClient $freeToGameClient): Response
    {
        $games = $freeToGameClient->getGames();
        return $this->render('game/index.html.twig', [
            'games' => $games,
        ]);
    }
}

