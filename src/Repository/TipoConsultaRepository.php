<?php

namespace App\Repository;

use App\Entity\TipoConsulta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoConsulta|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoConsulta|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoConsulta[]    findAll()
 * @method TipoConsulta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoConsultaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoConsulta::class);
    }

    // /**
    //  * @return TipoConsulta[] Returns an array of TipoConsulta objects
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
    public function findOneBySomeField($value): ?TipoConsulta
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
