<?php
/**
 * This file is part of meritesportif application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 4/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Service;


use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Entity\Club;
use App\Entity\Vote;
use App\Repository\CandidatRepository;
use Doctrine\ORM\EntityManagerInterface;

class VoteManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CandidatRepository
     */
    private $candidatRepository;

    public function __construct(EntityManagerInterface $entityManager, CandidatRepository $candidatRepository)
    {
        $this->entityManager = $entityManager;
        $this->candidatRepository = $candidatRepository;
    }

    public function handleVote(array $data, Club $club, Categorie $categorie)
    {
        foreach ($data as $idCandidat => $point) {

            $candidat = $this->candidatRepository->find($idCandidat);
            if (!$candidat) {
                continue;
            }

            if ($point > 0) {
                $vote = new Vote($categorie, $club, $candidat, $point);
                $this->entityManager->persist($vote);
            }

        }

    }
}