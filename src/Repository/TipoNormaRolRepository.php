<?php

namespace App\Repository;

use App\Entity\TipoNormaRol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoNormaRol|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoNormaRol|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoNormaRol[]    findAll()
 * @method TipoNormaRol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoNormaRolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoNormaRol::class);
    }

    // /**
    //  * @return TipoNormaRol[] Returns an array of TipoNormaRol objects
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
    public function findOneBySomeField($value): ?TipoNormaRol
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
