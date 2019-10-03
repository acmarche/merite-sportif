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

    public function __construct(VoteRepository $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function getVotesByClub(Club $club)
    {
        $rows = $this->voteRepository->getByClub($club);
        $categoriePrecedente = null;
        foreach ($rows as $data) {

            $categorie = $data->getCategorie();

            $vote = ['candidat' => $data->getCandidat(), 'position' => $data->getPosition()];

            $this->addVote($categorie, $vote);
        }

        return $this->votes;
    }

    public function addVote(Categorie $categorie, array $vote)
    {
        $this->votes[$categorie->getId()]['categorie'] = $categorie;
        $this->votes[$categorie->getId()]['votes'][] = $vote;
    }
}