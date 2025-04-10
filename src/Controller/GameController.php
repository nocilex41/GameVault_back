<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\GameRepository;
use App\Entity\Game;

#[Route('/api/game', name: 'app_game_')]
final class GameController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();
        return $this->json($games);
    }

    #[Route('/favorite', name: 'favorite_add', methods: ['POST'])]
    public function addFavorite(
        Request $request,
        GameRepository $gameRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ): JsonResponse {
        // Décoder le JSON envoyé par le front
        $gameJson = $request->getContent();

        // Décoder en tableau PHP
        $data = json_decode($gameJson, true);
        $game = $serializer->deserialize(json_encode($data['game']), Game::class, 'json');
        $entityManager->persist($game);
        $entityManager->flush();

       



        return $this->json(['message' => 'Game added to favorites', 'game' => [
            'id' => $game->getId(),
            'slug' => $game->getSlug(),
            'name' => $game->getName(),
            'isFavorite' => $game->isFavorite()
        ]]);
    }

    #[Route('/delete', name: 'favorite_remove', methods: ['DELETE'])]
    public function removeFavorite(
        Request $request,
        GameRepository $gameRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        // Décoder le JSON envoyé par le front
        $data = json_decode($request->getContent(), true);

        // Vérifier que le champ "slug" est présent
        if (!isset($data['slug'])) {
            return $this->json(['error' => 'Missing required field: slug'], 400);
        }

        // Rechercher le jeu par slug
        $game = $gameRepository->findOneBy(['slug' => $data['slug']]);

        if (!$game) {
            return $this->json(['error' => 'Game not found'], 404);
        }

        // Persister les changements et les enregistrer
        $entityManager->remove($game);
        $entityManager->flush();

        return $this->json(['success' => true]);
    }


}

