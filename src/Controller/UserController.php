<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_MERITE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $utilisateurRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        UserRepository $utilisateurRepository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="merite_user_index", methods={"GET","POST"})
     */
    public function index(Request $request): Response
    {
        $users = $this->utilisateurRepository->findAll();

        return $this->render(
            'user/index.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    /**
     * @Route("/new", name="merite_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $utilisateur = new User();
        $form = $this->createForm(UserType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateur->setPassword(
                $this->userPasswordEncoder->encodePassword($utilisateur, $utilisateur->getPassword())
            );
            $this->entityManager->persist($utilisateur);
            $this->entityManager->flush();

            return $this->redirectToRoute('merite_user_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render(
            'user/new.html.twig',
            [
                'user' => $utilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="merite_user_show", methods={"GET"})
     */
    public function show(User $utilisateur): Response
    {
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $utilisateur,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="merite_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $utilisateur): Response
    {
        $form = $this->createForm(UserEditType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute(
                'merite_user_show',
                ['id' => $utilisateur->getId()]
            );
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'user' => $utilisateur,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="merite_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($utilisateur);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('merite_user_index');
    }
}
