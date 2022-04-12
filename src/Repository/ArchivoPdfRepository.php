<?php

namespace App\Repository;

use App\Entity\ArchivoPdf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ArchivoPdf|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArchivoPdf|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArchivoPdf[]    findAll()
 * @method ArchivoPdf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivoPdfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArchivoPdf::class);
    }

    // /**
    //  * @return ArchivoPdf[] Returns an array of ArchivoPdf objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArchivoPdf
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
