<?php

namespace App\Repository;

use App\Entity\PruebaNorma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PruebaNorma|null find($id, $lockMode = null, $lockVersion = null)
 * @method PruebaNorma|null findOneBy(array $criteria, array $orderBy = null)
 * @method PruebaNorma[]    findAll()
 * @method PruebaNorma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PruebaNormaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PruebaNorma::class);
    }

    // /**
    //  * @return PruebaNorma[] Returns an array of PruebaNorma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PruebaNorma
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
