<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\GameRepository;

final class GameController extends AbstractController
{
    #[Route('/api/game', name: 'app_game', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();
        return $this->json($games);
    }

    #[Route('/api/game/delete', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, GameRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->json(['error' => 'ID manquant'], 400); // Retourner une erreur 400 pour ID manquant
        }

        $game = $repo->find($id);

        if (!$game) {
            return $this->json(['error' => 'Jeu introuvable'], 404); // Retourner une erreur 404 si le jeu n'est pas trouvé
        }

        $em->remove($game);
        $em->flush();

        return $this->json(['success' => true]);
    }


}

