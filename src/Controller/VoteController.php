<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Entity\Club;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\CandidatRepository;
use App\Repository\CategorieRepository;
use App\Repository\ClubRepository;
use App\Repository\VoteRepository;
use App\Service\VoteManager;
use App\Service\VoteService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 * @IsGranted("ROLE_MERITE_CLUB")
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
    /**
     * @var ClubRepository
     */
    private $clubRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var VoteRepository
     */
    private $voteRepository;
    /**
     * @var VoteService
     */
    private $voteService;
    /**
     * @var VoteManager
     */
    private $voteManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategorieRepository $categorieRepository,
        CandidatRepository $candidatRepository,
        ClubRepository $clubRepository,
        VoteRepository $voteRepository,
        VoteService $voteService,
        VoteManager $voteManager
    ) {
        $this->categorieRepository = $categorieRepository;
        $this->candidatRepository = $candidatRepository;
        $this->clubRepository = $clubRepository;
        $this->entityManager = $entityManager;
        $this->voteRepository = $voteRepository;
        $this->voteService = $voteService;
        $this->voteManager = $voteManager;
    }

    /**
     * @Route("/", name="vote_index", methods={"GET"})
     */
    public function index(): Response
    {
        $votes = $this->voteRepository->getAll();

        return $this->render(
            'vote/index.html.twig',
            [
                'votes' => $votes,
            ]
        );
    }

    /**
     * @Route("/intro", name="vote_intro", methods={"GET","POST"})
     */
    public function intro(): Response
    {
        $user = $this->getUser();
        $club = $user->getClub();

        $categorie = $this->categorieRepository->getFirst();

        return $this->render(
            'vote/intro.html.twig',
            [
                'club' => $club,
                'categorie' => $categorie,
            ]
        );
    }

    /**
     * @Route("/new/{ordre}", name="vote_new", methods={"GET","POST"})
     */
    public function new(Request $request, Categorie $categorie): Response
    {
        $user = $this->getUser();
        $club = $user->getClub();
        $candidats = $categorie->getCandidats();
        $data = [];

        $next = $this->categorieRepository->findNext($categorie->getOrdre());

        if ($this->voteService->voteExist($club, $categorie) && $next !== null) {
            return $this->redirectToRoute('vote_new', ['ordre' => $next->getOrdre()]);
        }

        $form = $this->createForm(VoteType::class, $data, ['categorie' => $categorie]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $this->voteManager->handleVote($data, $club, $categorie);

            $this->entityManager->flush();
            $this->addFlash('success', 'Votre vote a bien été pris en compte');

            $isComplete = $this->voteService->isComplete($club);

            if ($isComplete) {
                return $this->redirectToRoute('vote_show');
            }

            if ($next !== null) {
                return $this->redirectToRoute('vote_new', ['ordre' => $next->getOrdre()]);
            }

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
     * @Route("/show", name="vote_show", methods={"GET"})
     *
     */
    public function show(): Response
    {
        $user = $this->getUser();
        $club = $user->getClub();
        $votes = $this->voteService->getVotesByClub($club);
        $isComplete = $this->voteService->isComplete($club);

        return $this->render(
            'vote/show.html.twig',
            [
                'club' => $club,
                'votes' => $votes,
                'voteIsComplete' => $isComplete,
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
        $positions = [];
        if ($request->isXmlHttpRequest()) {
            $candidats = $request->request->get("candidats");
            if (is_array($candidats)) {
                foreach ($candidats as $position => $candidatId) {
                    $candidat = $this->candidatRepository->find($candidatId);
                    if ($candidat) {
                        $positions[] = $candidat->getId();
                    }
                }

                return new Response(implode('|', $positions));
            }
        }

        return new Response(null);
    }

    /**
     * @Route("/{id}", name="vote_delete", methods={"DELETE"})
     * @IsGranted("ROLE_MERITE_ADMIN")
     */
    public function delete(Request $request, Club $club): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {

            foreach ($club->getVotes() as $vote) {
                $this->entityManager->remove($vote);
            }
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('vote_index');
    }
}
