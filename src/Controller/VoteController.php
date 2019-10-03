<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 */
class VoteController extends AbstractController
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
     * @Route("/", name="vote_index", methods={"GET"})
     */
    public function index(VoteRepository $voteRepository): Response
    {
        return $this->render(
            'vote/index.html.twig',
            [
                'votes' => $voteRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/intro", name="vote_new_intro", methods={"GET","POST"})
     */
    public function begin(Request $request): Response
    {
        return $this->render(
            'vote/intro.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/new", name="vote_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = $this->categorieRepository->find(1);
        $candidats = $categorie->getCandidats();
        $data = [];

        $form = $this->createForm(VoteType::class, $data, ['categorie' => $categorie]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dump($form->getData());

            //$entityManager->persist($vote);
            //$entityManager->flush();

            //  return $this->redirectToRoute('vote_index');
        }

        return $this->render(
            'vote/new.html.twig',
            [
                'categorie' => $categorie,
                'candidats' => $candidats,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render(
            'vote/show.html.twig',
            [
                'vote' => $vote,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="vote_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vote $vote): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vote_index');
        }

        return $this->render(
            'vote/edit.html.twig',
            [
                'vote' => $vote,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/trier", name="vote_trier", methods={"GET","POST"})
     */
    public function trier(Request $request): Response
    {

        if ($request->isXmlHttpRequest()) {
            $candidats = $request->request->get("candidats");
            if (is_array($candidats)) {
                foreach ($candidats as $position => $candidatId) {
                    $candidat = $this->candidatRepository->find($candidatId);
                    if ($candidat) {
                        dump($candidat->getNom());
                    }
                }

                // $this->santeQuestionRepository->save();

                return new Response('<div class="alert alert-success">Tri enregistré</div>');
            }

            return new Response('<div class="alert alert-error">Erreur</div>');
        }

        return new Response('<div class="alert alert-error">Erreur</div>');

    }

    /**
     * @Route("/{id}", name="vote_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Vote $vote): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vote_index');
    }
}
