<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Form\CandidatType;
use App\Form\PropositionType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/proposition")
 * @IsGranted("ROLE_MERITE_CLUB")
 */
class PropositionController extends AbstractController
{
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;
    /**
     * @var CandidatRepository
     */
    private $candidatRepository;

    public function __construct(CategorieRepository $categorieRepository, CandidatRepository $candidatRepository)
    {
        $this->categorieRepository = $categorieRepository;
        $this->candidatRepository = $candidatRepository;
    }

    /**
     * @Route("/", name="proposition_index", methods={"GET"})
     */
    public function index(CandidatRepository $candidatRepository): Response
    {
        $user = $this->getUser();
        $club = $user->getClub();
        $categories = $this->categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $candidat = $this->candidatRepository->isAlreadyProposed($club, $categorie);
            if ($candidat) {
                $categorie->setComplete(true);
                $categorie->setProposition($candidat->getId());
            }
        }

        return $this->render(
            'proposition/index.html.twig',
            [
                'categories' => $categories,
                'candidats' => $candidatRepository->getAll(),
            ]
        );
    }

    /**
     * @Route("/new/{id}", name="proposition_new", methods={"GET","POST"})
     */
    public function new(Request $request, Categorie $categorie): Response
    {
        $user = $this->getUser();
        $club = $user->getClub();

        if ($this->candidatRepository->isAlreadyProposed($club, $categorie)) {
            $this->addFlash('warning', 'Vous avez déjà proposé un candidat pour cette catégorie');

            return $this->redirectToRoute('proposition_index');
        }

        $candidat = new Candidat();
        $candidat->setValidate(false);
        $candidat->setAddBy($club->getEmail());
        $candidat->setCategorie($categorie);

        $form = $this->createForm(PropositionType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($candidat);
            $entityManager->flush();

            $this->addFlash('success', 'Le candidat a bien été proposé');

            return $this->redirectToRoute('proposition_index');
        }

        return $this->render(
            'proposition/new.html.twig',
            [
                'categorie' => $categorie,
                'candidat' => $candidat,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="proposition_show", methods={"GET"})
     * @Security("is_granted('CANDIDAT_EDIT', candidat)", statusCode=404)
     */
    public function show(Candidat $candidat): Response
    {
        return $this->render(
            'proposition/show.html.twig',
            [
                'candidat' => $candidat,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="proposition_edit", methods={"GET","POST"})
     * @Security("is_granted('CANDIDAT_EDIT', candidat)", statusCode=404)
     */
    public function edit(Request $request, Candidat $candidat): Response
    {
        $form = $this->createForm(PropositionType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le candidat a bien été modifié');

            return $this->redirectToRoute('proposition_index');
        }

        return $this->render(
            'candidat/edit.html.twig',
            [
                'candidat' => $candidat,
                'form' => $form->createView(),
            ]
        );
    }

}
