<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use App\Repository\TranslationRepository;
use App\Repository\WordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('api/admin/')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly LanguageRepository $languageRepository,
        private readonly WordRepository $wordRepository,
        private readonly TranslationRepository $translationRepository,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('summary', methods: 'GET')]
    public function index(): JsonResponse
    {
        $languages = $this->languageRepository->findAll();
        $words = $this->wordRepository->findAll();
        $translations = $this->translationRepository->findAll();

        return $this->json([
            'languages' => $this->normalizer->normalize($languages),
            'words' => $this->normalizer->normalize($words),
            'translations' => $this->normalizer->normalize($translations),
        ]);
    }
}
