<?php


namespace App\Service;


use App\Entity\Club;
use App\Repository\CandidatRepository;
use App\Repository\CategorieRepository;

class PropositionService
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

    public function isComplete(Club $club): bool
    {
        $count = 0;
        $categories = $this->categorieRepository->findAll();

        foreach ($categories as $categorie) {
            $candidat = $this->candidatRepository->isAlreadyProposed($club, $categorie);
            if ($candidat) {
                $count++;
            }
        }
        return $count == count($categories);
    }


}