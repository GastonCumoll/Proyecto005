<?php

namespace App\Repository;

use App\Entity\RelacionInversa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelacionInversa|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelacionInversa|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelacionInversa[]    findAll()
 * @method RelacionInversa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelacionInversaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelacionInversa::class);
    }

    // /**
    //  * @return RelacionInversa[] Returns an array of RelacionInversa objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelacionInversa
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
