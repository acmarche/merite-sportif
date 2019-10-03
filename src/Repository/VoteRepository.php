<?php

namespace App\Repository;

use App\Entity\Club;
use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('vote')
            ->orderBy('vote.categorie', 'ASC')
            ->orderBy('vote.candidat', 'ASC')
            ->orderBy('vote.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Club $club
     * @return Vote[]
     */
    public function getByClub(Club $club)
    {
        return $this->createQueryBuilder('vote')
            ->andWhere('vote.club = :club')
            ->setParameter('club', $club)
            ->orderBy('vote.categorie', 'ASC')
            ->orderBy('vote.candidat', 'ASC')
            ->orderBy('vote.position', 'ASC')
            ->getQuery()
            ->getResult();

    }
}
