<?php

namespace App\Repository;

use App\Entity\Faltantes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Faltantes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faltantes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faltantes[]    findAll()
 * @method Faltantes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaltantesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faltantes::class);
    }

    // /**
    //  * @return Faltantes[] Returns an array of Faltantes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Faltantes
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
