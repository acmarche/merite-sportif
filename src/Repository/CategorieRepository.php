<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function getFirst(): Categorie
    {
        return $this->createQueryBuilder('categorie')
            ->andWhere('categorie.ordre = :ordre')
            ->setParameter('ordre', 1)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findNext(int $positionCurrent): Categorie
    {
        return $this->createQueryBuilder('categorie')
            ->andWhere('categorie.ordre = :ordre')
            ->setParameter('ordre', ++$positionCurrent)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
