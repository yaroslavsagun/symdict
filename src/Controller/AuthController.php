<?php

namespace App\Controller;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Enum\Role;
use App\Repository\AccessTokenRepository;
use App\Repository\UserRepository;
use App\Request\Auth\LoginRequest;
use App\Request\Auth\RegisterRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route("api/auth/")]
class AuthController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly Security $security,
        private readonly AccessTokenRepository $accessTokenRepository
    ) {
    }

    #[Route('login', methods: ['POST'])]
    public function login(LoginRequest $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        /** @var User $user */
        $user = $this->userRepository->loadUserByIdentifier($request->login);
        if (!$user) {
            return $this->json(['success' => false, 'errors' => ['User with a given login does not exist']], 400);
        }
        if (!$hasher->isPasswordValid($user, $request->password)) {
            return $this->json(['success' => false, 'errors' => ['Incorrect password']], 400);
        }
        $tokenObject = (new AccessToken())->setUser($user);
        $this->entityManager->persist($tokenObject);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'token' => $tokenObject->getToken(),
            'role' => $user->getRole()->slug(),
        ]);
    }

    #[Route('register', methods: ['POST'])]
    public function register(RegisterRequest $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        if ($this->userRepository->findOneBy(['email' => $request->email])) {
            return $this->json(['success' => false, 'errors' => ['Email is already taken']], 400);
        }
        $user = new User();
        $password = $hasher->hashPassword($user, $request->password);
        $user->setRole(Role::USER)->setName($request->name)->setEmail($request->email)->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $tokenObject = (new AccessToken())->setUser($user);
        $this->entityManager->persist($tokenObject);
        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'token' => $tokenObject->getToken(),
            'role' => $user->getRole()->slug(),
        ]);
    }

    #[Route('logout', methods: ['POST'])]
    public function logout(Request $request): Response
    {
        $this->security->logout(false);
        $token = $this->getToken($request);
        $tokenObject = $this->accessTokenRepository->findOneBy(['token' => $token]);
        $this->entityManager->remove($tokenObject);
        $this->entityManager->flush();

        return $this->json(['success' => true]);
    }

    private function getToken(Request $request): string|false
    {
        $authHeader = $request->headers->get('authorization');
        if (!$authHeader) {
            return false;
        }

        return trim(str_replace('Bearer', '', $authHeader));
    }
}
