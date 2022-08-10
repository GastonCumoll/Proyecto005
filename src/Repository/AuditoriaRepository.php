<?php

namespace App\Repository;

use App\Entity\Auditoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Auditoria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Auditoria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Auditoria[]    findAll()
 * @method Auditoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuditoriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Auditoria::class);
    }

    // /**
    //  * @return Auditoria[] Returns an array of Auditoria objects
    //  */
    
    public function findByNorma($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.norma = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            // ->getResult()
        ;
    }
    public function findByNormaTexto($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.norma = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    

    /*
    public function findOneBySomeField($value): ?Auditoria
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
