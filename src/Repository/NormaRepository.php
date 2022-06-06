<?php

namespace App\Repository;

use App\Entity\Norma;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Norma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Norma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Norma[]    findAll()
 * @method Norma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * 
 */
class NormaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Norma::class);
    }

    // /**
    //  * @return Norma[] Returns an array of Norma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findUnaPalabraDentroDelTitulo($palabra): array
    {
        $retorno=$this->createQueryBuilder('p')->where('p.titulo LIKE :titulo')->setParameter('titulo','%'.$palabra.'%')->orderBy('p.titulo','ASC');
        $query=$retorno->getQuery();
        return $query->execute();
    }

    public function findNormas($titulo,$numero,$año,$tipo): Query
    {

        $consulta=$this->createQueryBuilder('p');
        if($titulo){
            $consulta->andWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$titulo.'%');
        }if($numero){
            $consulta->andWhere('p.numero LIKE :numero')->setParameter('numero','%'.$numero.'%');
        }if($año){
            $consulta->andWhere('p.fechaPublicacion LIKE :fecha')->setParameter('fecha','%'.$año.'%');
        }
        if($tipo){
            $consulta->andWhere('p.tipoNorma = :tipo')->setParameter('tipo',$tipo->getId());
        }
            $consulta->orderBy('p.titulo','ASC');

        $query=$consulta->getQuery();

        return $query;
    }
    public function findArrayDePalabras($arreglo): Query
    {
        $tam=sizeof($arreglo);
        $consulta=$this->createQueryBuilder('p')->where('p.titulo LIKE :titulo')->setParameter('titulo','%'.$arreglo[0].'%');
        for ($i=1; $i <$tam ; $i++)
        { 
            $consulta->orWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$arreglo[$i].'%');
        }
        $consulta->orderBy('p.titulo','ASC');
        $query=$consulta->getQuery();

        //dd($query);

        return $query;
    }

    public function findUnNumero($palabra): array
    {
        $retorno=$this->createQueryBuilder('p')->where('p.numero LIKE :numero')->setParameter('numero','%'.$palabra.'%')->orderBy('p.titulo','ASC');
        $query=$retorno->getQuery();
        return $query->execute();
    }

    public function findUnAño($palabra): array
    {
        $retorno=$this->createQueryBuilder('p')->where('p.fechaPublicacion LIKE :fecha')->setParameter('fecha','%'.$palabra.'%')->orderBy('p.titulo','ASC');
        $query=$retorno->getQuery();
        return $query->execute();
    }



    /*
    public function findOneBySomeField($value): ?Norma
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
