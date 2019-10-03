<?php

namespace App\Controller;

use App\Entity\Token;
use App\Service\TokenManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TokenController
 * @package AcMarche\Volontariat\Controller
 * @Route("/token")
 */
class TokenController extends AbstractController
{
    /**
     * @var TokenManager
     */
    private $tokenManager;

    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @Route("/",name="volontariat_token")
     *
     */
    public function index()
    {
        $this->tokenManager->createForAllUsers();
    }

    /**
     * @Route("/{value}",name="app_token_show")
     *
     */
    public function show(Request $request, Token $token)
    {
        if ($this->tokenManager->isExpired($token)) {
            $this->addFlash('error', "Cette url a expirée");

            return $this->redirectToRoute('merite_home');
        }

        $user = $token->getUser();
        $this->tokenManager->loginUser($request, $user, 'main');

        return $this->redirectToRoute('merite_home');

    }

}
