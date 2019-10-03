<?php

namespace App\Service;

use App\Entity\Token;
use App\Entity\User;
use App\Repository\TokenRepository;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class TokenManager
{
    /**
     * @var TokenRepository
     */
    private $tokenRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var GuardAuthenticatorHandler
     */
    private $guardAuthenticatorHandler;
    /**
     * @var AppAuthenticator
     */
    private $appAuthenticator;

    public function __construct(
        GuardAuthenticatorHandler $guardAuthenticatorHandler,
        AppAuthenticator $appAuthenticator,
        TokenRepository $tokenRepository,
        UserRepository $userRepository
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->userRepository = $userRepository;
        $this->guardAuthenticatorHandler = $guardAuthenticatorHandler;
        $this->appAuthenticator = $appAuthenticator;
    }

    public function getInstance(User $user)
    {
        if (!$token = $this->tokenRepository->findOneBy(['user' => $user])) {
            $token = new Token();
            $token->setUser($user);
            $this->tokenRepository->persist($token);
        }

        return $token;
    }

    public function generate(User $user)
    {
        $token = $this->getInstance($user);
        try {
            $token->setValue(bin2hex(random_bytes(20)));
        } catch (\Exception $e) {
        }

        $expireTime = new \DateTime('+90 day');
        $token->setExpireAt($expireTime);

        $this->tokenRepository->save();

        return $token;
    }

    public function isExpired(Token $token)
    {
        $today = new \DateTime('today');

        return $today > $token->getExpireAt();
    }

    public function createForAllUsers()
    {
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $this->generate($user);
        }
    }

    public function loginUser(Request $request, User $user, $firewallName)
    {
        $this->guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $this->appAuthenticator,
            $firewallName
        );
    }
}