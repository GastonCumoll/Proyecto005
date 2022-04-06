<?php

namespace App\Repository;

use App\Entity\TipoRelacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoRelacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoRelacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoRelacion[]    findAll()
 * @method TipoRelacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoRelacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoRelacion::class);
    }

    // /**
    //  * @return TipoRelacion[] Returns an array of TipoRelacion objects
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
    public function findOneBySomeField($value): ?TipoRelacion
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
