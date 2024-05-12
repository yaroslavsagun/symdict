<?php

namespace App\Controller;

use App\Entity\Language;
use App\Repository\LanguageRepository;
use App\Request\Language\CreateLanguageRequest;
use App\Request\Language\UpdateLanguageRequest;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('api/language/')]
class LanguageController extends AbstractController
{
    public function __construct(
        private readonly LanguageRepository $languageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $languages = $this->languageRepository->findAll();

        return $this->json(['success' => true, 'languages' => $this->normalizer->normalize($languages)]);
    }

    #[Route('create', methods: ['POST'])]
    public function create(CreateLanguageRequest $request): JsonResponse
    {
        if ($this->languageRepository->findOneBy(['slug' => $request->slug])) {
            return $this->json(['success' => false, 'errors' => ['Language with given slug already exists']], 422);
        }
        $language = new Language();
        $language->setSlug($request->slug)->setName($request->name);
        $this->entityManager->persist($language);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'language' => $this->normalizer->normalize($language)]);
    }

    #[Route('{languageId}/update', methods: ['POST', 'PUT'])]
    public function update(int $languageId, UpdateLanguageRequest $request): JsonResponse
    {
        $language = $this->languageRepository->find($languageId);
        if (!$language) {
            return $this->json(['success' => false, 'errors' => ['Language with given ID does not exist']], 422);
        }

        $criteria = Criteria::create()->where(Criteria::expr()->neq('id', $language->getId()))
            ->andWhere(Criteria::expr()->eq('slug', $request->slug));
        if (!$this->languageRepository->matching($criteria)->count()) {
            $language->setSlug($request->slug);
        } else {
            return $this->json(['success' => false, 'errors' => ['slug is already in use']], 422);
        }
        $language->setName($request->name);

        $this->entityManager->flush();

        return $this->json(['success' => true, 'language' => $language]);
    }

    #[Route('{languageId}/delete', methods: ['POST', 'DELETE'])]
    public function delete(int $languageId): JsonResponse
    {
        $language = $this->languageRepository->find($languageId);
        if (!$language) {
            return $this->json(['success' => false, 'errors' => ['Language with given ID does not exist']], 422);
        }

        $this->entityManager->remove($language);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }
}
