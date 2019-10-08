<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 3/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Service;


use App\Entity\Categorie;
use App\Entity\Club;
use App\Repository\CategorieRepository;
use App\Repository\VoteRepository;

class VoteService
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;

    /**
     * @var array
     */
    private $votes = [];
    /**
     * @var CategorieRepository
     */
    private $categorieRepository;

    public function __construct(VoteRepository $voteRepository, CategorieRepository $categorieRepository)
    {
        $this->voteRepository = $voteRepository;
        $this->categorieRepository = $categorieRepository;
    }

    public function voteExist(Club $club, Categorie $categorie): bool
    {
        if ($this->voteRepository->getByClubAndCategorie($club, $categorie)) {
            return true;
        }

        return false;
    }

    public function getVotesByClub(Club $club)
    {
        $rows = $this->voteRepository->getByClub($club);
        $categoriePrecedente = null;
        foreach ($rows as $data) {

            $categorie = $data->getCategorie();

            $vote = ['candidat' => $data->getCandidat(), 'point' => $data->getPoint()];

            $this->addVote($categorie, $vote);
        }

        return $this->votes;
    }

    public function addVote(Categorie $categorie, array $vote)
    {
        $this->votes[$categorie->getId()]['categorie'] = $categorie;
        $this->votes[$categorie->getId()]['votes'][] = $vote;
    }

    public function isComplete(Club $club): bool
    {
        $points = 0;
        foreach ($this->categorieRepository->findAll() as $categorie) {
            $votes = $this->voteRepository->getByClubAndCategorie($club, $categorie);
            foreach ($votes as $vote) {
                $points += $vote->getPoint();
            }
        }

        return $points === 9;
    }

    /**
     * @param Club[] $clubs
     */
    public function setIsComplete(array $clubs)
    {
        foreach ($clubs as $club) {
            $club->setvoteIsComplete($this->isComplete($club));
        }
    }

    public function getVotesByCategorie(Categorie $categorie)
    {
        $candidats = [];
        $votes = $this->voteRepository->getByCategorie($categorie);
        foreach ($votes as $vote) {
            $candidat = $vote->getCandidat();
            $point = $vote->getPoint();
            if (!isset($candidats[$candidat->getId()])) {
                $candidats[$candidat->getId()]['candidat'] = $candidat;
                $candidats[$candidat->getId()]['point'] = $point;
            } else {
                $candidats[$candidat->getId()]['point'] += $point;
            }
        }

        usort(
            $candidats,
            function ($a, $b) {
                return (int)$b['point'] <=> (int)$a['point'];
            }
        );

        return $candidats;
    }
}