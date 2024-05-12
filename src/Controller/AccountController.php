<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\Account\SaveAccountRequest;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('api/account/')]
class AccountController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly UserRepository $userRepository,
        private readonly NormalizerInterface $normalizer,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    #[Route('', methods: ['GET'])]
    public function user(): Response
    {
        $user = $this->userRepository->loadUserByIdentifier($this->security->getUser()->getUserIdentifier());

        return $this->json(['success' => true, 'user' => $this->normalizer->normalize($user)]);
    }

    #[Route('save', methods: ['POST'])]
    public function save(SaveAccountRequest $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if ($request->name) {
            $user->setName($request->name);
        }
        if ($request->email) {
            $criteria = Criteria::create()->where(Criteria::expr()->neq('id', $user->getId()))
                ->andWhere(Criteria::expr()->eq('email', $request->email));
            if (!$this->userRepository->matching($criteria)->count()) {
                $user->setEmail($request->email);
            } else {
                return $this->json(['success' => false, 'errors' => ['email is already in use']]);
            }
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json(['success' => true, 'user' => $this->normalizer->normalize($user)]);
    }
}