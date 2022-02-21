<?php

namespace App\Repository;

use App\Entity\TipoNorma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoNorma|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoNorma|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoNorma[]    findAll()
 * @method TipoNorma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoNormaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoNorma::class);
    }

    // /**
    //  * @return TipoNorma[] Returns an array of TipoNorma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoNorma
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
