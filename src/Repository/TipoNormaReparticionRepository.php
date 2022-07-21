<?php

namespace App\Repository;

use App\Entity\TipoNormaReparticion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoNormaReparticion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoNormaReparticion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoNormaReparticion[]    findAll()
 * @method TipoNormaReparticion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoNormaReparticionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoNormaReparticion::class);
    }

    // /**
    //  * @return TipoNormaReparticion[] Returns an array of TipoNormaReparticion objects
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
    public function findOneBySomeField($value): ?TipoNormaReparticion
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
