<?php

namespace App\Controller;

use App\Entity\Word;
use App\Repository\LanguageRepository;
use App\Repository\WordRepository;
use App\Request\Word\CreateWordRequest;
use App\Request\Word\UpdateWordRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('api/word/')]
class WordController extends AbstractController
{
    public function __construct(
        private readonly WordRepository $wordRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer,
        private readonly LanguageRepository $languageRepository
    ) {
    }

    #[Route('', methods: 'GET')]
    public function index(): JsonResponse
    {
        $words = $this->wordRepository->findAll();

        return $this->json(['success' => true, 'words' => $this->normalizer->normalize($words)]);
    }

    #[Route('by-language/{languageId}', methods: 'GET')]
    public function getByLanguage(int $languageId): JsonResponse
    {
        $language = $this->languageRepository->find($languageId);
        if (!$language) {
            return $this->json(['success' => false, 'errors' => ['Language does not exist']], 422);
        }
        $words = $this->wordRepository->findBy(['language' => $language]);

        return $this->json(['success' => true, 'words' => $this->normalizer->normalize($words)]);
    }

    #[Route('create', methods: 'POST')]
    public function create(CreateWordRequest $request): JsonResponse
    {
        $language = $this->languageRepository->find($request->languageId);
        $word = strtolower($request->word);
        if (!$language) {
            return $this->json(['success' => false, 'errors' => ['Language does not exist']], 422);
        }
        if ($this->wordRepository->findOneBy(['language' => $language, 'word' => $word])) {
            return $this->json(['success' => false, 'errors' => ['Word already exists for the given language']], 422);
        }

        $wordObj = new Word();
        $wordObj->setLanguage($language)->setWord($word);
        $this->entityManager->persist($wordObj);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'word' => $this->normalizer->normalize($wordObj)]);
    }

    #[Route('{wordId}/update', methods: ['POST', 'PUT'])]
    public function update(int $wordId, UpdateWordRequest $request): JsonResponse
    {
        $word = $this->wordRepository->find($wordId);
        if (!$word) {
            return $this->json(['success' => false, 'errors' => ['Word does not exist']], 422);
        }

        $word->setWord($request->word);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'word' => $this->normalizer->normalize($word)]);
    }

    #[Route('{wordId}/delete', methods: ['POST', 'DELETE'])]
    public function delete(int $wordId): JsonResponse
    {
        $word = $this->wordRepository->find($wordId);
        if (!$word) {
            return $this->json(['success' => false, 'errors' => ['Word does not exist']], 422);
        }

        $this->entityManager->remove($word);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }
}
