<?php

namespace App\Repository;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Entity\Club;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Candidat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidat[]    findAll()
 * @method Candidat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidat::class);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getQueryBuilder(Categorie $categorie)
    {
        return $this->createQueryBuilder('candidat')
            ->andWhere('candidat.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('candidat.nom', 'ASC');
    }

    public function getByCategorie(Categorie $categorie)
    {
        return $this->createQueryBuilder('candidat')
            ->andWhere('candidat.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult();
    }

    public function isAlreadyProposed(Club $club, Categorie $categorie): bool
    {
        $results = $this->createQueryBuilder('candidat')
            ->andWhere('candidat.categorie = :categorie')
            ->setParameter('categorie', $categorie)
            ->andWhere('candidat.add_by = :user')
            ->setParameter('user', $club->getEmail())
            ->orderBy('RAND()')
            ->getQuery()
            ->getResult();

        return count($results) > 0;
    }
}
