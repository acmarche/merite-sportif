<?php

namespace App\Controller;

use App\Entity\Token;
use App\Service\TokenManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

/**
 * Class TokenController
 *
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
     * @Route("/",name="merite_token_create")
     * @IsGranted("ROLE_MERITE_ADMIN")
     */
    public function index()
    {
        $this->tokenManager->createForAllUsers();
        $this->addFlash('success', 'Les tokens ont bien été générés');

        return $this->redirectToRoute('merite_user_index');
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

        return $this->redirectToRoute('vote_intro');

    }

}
