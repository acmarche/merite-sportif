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
use Doctrine\ORM\EntityManagerInterface;

class VoteManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handleVote(array $data, Club $club, Categorie $categorie)
    {
        $positions = explode('|', $data['positions']);
        /**
         * @var Candidat[] $candidats
         */
        $candidats = $data['candidats'];
        foreach ($candidats as $candidat) {

            $vote = new Vote($categorie, $club, $candidat);
            if (is_array($positions)) {
                $key = array_search($candidat->getId(), $positions);
                if ($key !== null) {
                    $vote->setPosition($key);
                }
            }
            $this->entityManager->persist($vote);
        }

    }
}