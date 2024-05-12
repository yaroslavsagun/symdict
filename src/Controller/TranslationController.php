<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Repository\LanguageRepository;
use App\Repository\TranslationRepository;
use App\Repository\WordRepository;
use App\Request\Translation\CreateTranslationRequest;
use App\Request\Translation\GetTranslationRequest;
use App\Request\Translation\UpdateTranslationRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('api/translation/')]
class TranslationController extends AbstractController
{
    public function __construct(
        private readonly TranslationRepository $translationRepository,
        private readonly WordRepository $wordRepository,
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $translations = $this->translationRepository->findAll();

        return $this->json(['success' => true, 'translations' => $this->normalizer->normalize($translations)]);
    }

    #[Route('create', methods: 'POST')]
    public function create(CreateTranslationRequest $request): JsonResponse
    {
        $originalWord = $this->wordRepository->find($request->originalWordId);
        $translationWord = $this->wordRepository->find($request->translationWordId);
        if (!$originalWord || !$translationWord) {
            return $this->json(['success' => false, 'errors' => ['Word does not exist']], 422);
        }
        if ($originalWord->getLanguage()->getId() === $translationWord->getLanguage()->getId()) {
            return $this->json(['success' => false, 'errors' => ['Words are from one language']], 422);
        }
        $criteria = ['original_word' => $originalWord, 'translation_word' => $translationWord];
        if ($this->translationRepository->findOneBy($criteria)) {
            return $this->json(['success' => false, 'errors' => ['Translation already exists']], 422);
        }

        $translation = new Translation();
        $translation->setOriginalLanguage($originalWord->getLanguage())
            ->setOriginalWord($originalWord)
            ->setTranslationLanguage($translationWord->getLanguage())
            ->setTranslationWord($translationWord);
        $this->entityManager->persist($translation);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'translation' => $this->normalizer->normalize($translation)]);
    }

    #[Route('translate', methods: ['POST'])]
    public function translate(GetTranslationRequest $request): JsonResponse
    {
        $originalLanguage = $this->languageRepository->find($request->originalLanguage);
        $originalWord = $this->wordRepository->find($request->originalWord);
        $translationLanguage = $this->languageRepository->find($request->translationLanguage);
        if (!$originalLanguage || !$originalWord || !$translationLanguage) {
            return $this->json(['success' => false, 'errors' => ['Can not translate']], 422);
        }
        $translation = $this->translationRepository->findOneBy([
            'original_language' => $originalLanguage,
            'original_word' => $originalWord,
            'translation_language' => $translationLanguage,
        ]);
        if (!$translation) {
            return $this->json(['success' => false, 'errors' => ['Translation does not exist']], 422);
        }

        return $this->json(['success' => true, 'translation' => $this->normalizer->normalize($translation)]);
    }

    #[Route('{translationId}/update', methods: ['POST', 'PUT'])]
    public function update(int $translationId, UpdateTranslationRequest $request): JsonResponse
    {
        $translation = $this->translationRepository->find($translationId);
        if (!$translation) {
            return $this->json(['success' => false, 'errors' => ['Translation does not exist']], 422);
        }
        $originalWord = $this->wordRepository->find($request->originalWordId);
        $translationWord = $this->wordRepository->find($request->translationWordId);
        if (!$originalWord || !$translationWord) {
            return $this->json(['success' => false, 'errors' => ['Word does not exist']], 422);
        }
        if ($originalWord->getLanguage()->getId() === $translationWord->getLanguage()->getId()) {
            return $this->json(['success' => false, 'errors' => ['Words are from one language']], 422);
        }
        $criteria = ['original_word' => $originalWord, 'translation_word' => $translationWord];
        if ($this->translationRepository->findOneBy($criteria)) {
            return $this->json(['success' => false, 'errors' => ['Translation already exists']], 422);
        }

        $translation->setOriginalLanguage($originalWord->getLanguage())
            ->setOriginalWord($originalWord)
            ->setTranslationLanguage($translationWord->getLanguage())
            ->setTranslationWord($translationWord);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'translation' => $this->normalizer->normalize($translation)]);
    }

    #[Route('{translationId}/delete', methods: ['POST', 'DELETE'])]
    public function delete(int $translationId): JsonResponse
    {
        $translation = $this->translationRepository->find($translationId);
        if (!$translation) {
            return $this->json(['success' => false, 'errors' => ['Translation does not exist']], 422);
        }
        $this->entityManager->remove($translation);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }
}
