<?php

namespace App\Repository;

use App\Entity\Norma;
use App\Entity\TipoNorma;
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
    //findAllQuery : busca todas las normas, y hace un join con tipoNorma para poder ordenar
    public function findAllQuery(): Query
    {
        $consulta=$this->createQueryBuilder('p')->select('p')->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->where("p.estado = 'Publicada'")->andWhere("p.publico = 1")
        ->orderBy('p.id','DESC');
        $query=$consulta->getQuery();
        return $query;
    }
    //la diferencia entre findAllQuery y findAllQueryS es que la segunda se usa si la sesion esta definida
    //findAllQuery : busca todas las normas, y hace un join con tipoNorma para poder ordenar
    public function findAllQueryS($reparticion,$rol): Query
    {
        if($rol=="DIG_CONSULTOR"){
            $consultaAux2=" AND p.estado = 'Publicada'";
            $consultaAux="p.estado = 'Publicada' AND p.publico =1";

        }else{
            $consultaAux="p.estado = 'Publicada' AND p.publico =1";
            $consultaAux2="";
        }
        
        $consulta=$this->createQueryBuilder('p')->select('p')->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaReparticion','tnr','WITH','tnr.tipoNormaId = t.id')->where("tnr.reparticionId='".$reparticion->getId()."'".$consultaAux2)
        ->orWhere($consultaAux)
        ->orderBy('p.id','DESC');
        $query=$consulta->getQuery();
        //dd($query);
        return $query;
    }
    public function findUnaPalabraDentroDelTitulo($palabra): Query
    {
        $consultaAux="p.estado = 'Publicada' AND p.publico =1";
        $retorno=$this->createQueryBuilder('p')->where('p.titulo LIKE :titulo')->setParameter('titulo','%'.$palabra.'%')
        ->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->andWhere($consultaAux)
        ->orderBy('p.id','DESC');
        $query=$retorno->getQuery();
        // dd($query);
        return $query;
    }
    public function findUnaPalabraDentroDelTituloSession($roles,$reparticion,$palabra): Query
    {
        if($roles[0]=="DIG_CONSULTOR"){
            $consultaAux=" AND p.estado = 'Publicada')";
            $consultaAux2=" OR (p.estado = 'Publicada' AND p.publico =1)";
        }else{
            $consultaAux=")OR (p.estado = 'Publicada' AND p.publico =1)";
            $consultaAux2="";
        }
        //$consultaAux="p.estado = 'Publicada' AND p.publico =1";
        $retorno=$this->createQueryBuilder('p')->where('p.titulo LIKE :titulo')->setParameter('titulo','%'.$palabra.'%')
        ->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')

        ->join('App\Entity\TipoNormaReparticion','tnr','WITH','tnr.tipoNormaId = t.id')->orderBy('p.id','DESC');
        //->andWhere($consultaAux)

        $retorno->andWhere("(tnr.reparticionId = '".$reparticion->getId()."'".$consultaAux.$consultaAux2);
        
        $query=$retorno->getQuery();
        //dd($query);
        return $query;
    }

    public function findBorradores($roles,$reparticion){
        // dd($reparticion->getId());
        $consulta=$this->createQueryBuilder('p');
        $consulta->where('p.estado = :b')->setParameter('b','Borrador')->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaRol','tr','WITH','tr.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaReparticion','tnr','WITH','tnr.tipoNormaId = tr.tipoNorma')
        ->orderBy('p.id','ASC');
        foreach ($roles as $rol) {
            $consulta->andWhere("tr.nombreRol='".$rol."'");
        }
        $consulta->andWhere("tnr.reparticionId = '".$reparticion->getId()."'");
        //dd($consulta);
        $query=$consulta->getQuery();
        //dd($query);
        return $query;
    }

    public function findListas($roles,$reparticion){
        $consulta=$this->createQueryBuilder('p');
        $consulta->where('p.estado = :l')->setParameter('l','Lista')->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaRol','tr','WITH','tr.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaReparticion','tnr','WITH','tnr.tipoNormaId = tr.tipoNorma')
        ->orderBy('p.id','ASC');
        foreach ($roles as $rol) {
            $consulta->andWhere("tr.nombreRol='".$rol."'");
        }
        $consulta->andWhere("tnr.reparticionId='".$reparticion->getId()."'");
        $query=$consulta->getQuery();
        return $query;
    }

    //busqueda de los filtros
    public function findNormas($titulo,$numero,$año,$tipo,$arrayDeNormas): Query 
    {
        $cont=0;
        $tam=count($arrayDeNormas);
        
        $consulta=$this->createQueryBuilder('p');
        $consulta1="";
        if($arrayDeNormas){//entra si hay mas de una norma
            //if(count($arrayDeNormas)>1){
                //$consulta->where('p.id = :id');
                for ($i=0; $i < $tam; $i++) {
                    if($i==0){
                        $consulta1.= "(p.id = ".$arrayDeNormas[$i]->getId();
                    //     $consulta->setParameter('id',$arrayDeNormas[$i]);
                    }else{
                        $consulta1.= "OR(p.id = " .$arrayDeNormas[$i]->getId();
                    //     $consulta->orWhere($consulta->expr()->orX(
                    //     'p.id = :id'))->setParameter('id',$arrayDeNormas[$i]);
                    }
                        if($titulo){
                            $consulta1.= " AND p.titulo LIKE '%".$titulo."%'";
                            //$consulta->andWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$titulo.'%');
                        }if($numero){
                            $consulta1.= " AND p.numero LIKE '%".$numero."%'";
                            //$consulta->andWhere('p.numero LIKE :numero')->setParameter('numero','%'.$numero.'%');
                        }if($año){
                            $consulta1.= " AND p.fechaPublicacion LIKE '%".$año."%'" ;
                            //$consulta->andWhere('p.fechaPublicacion LIKE :fecha')->setParameter('fechaPublicacion', '%'.$año.'%');
                        }
                        if($tipo){
                            $consulta1.= " AND p.tipoNorma = ".$tipo;
                            //$consulta->andWhere('p.tipoNorma = :tipo')->setParameter('tipoNorma',$tipo);
                        }
                        $consulta1.= ")";
                }
                
                $consulta=$this->createQueryBuilder('p')->where($consulta1);

            //dd($consulta1);
            //}
        }else{
            
            if($titulo){
                    $consulta->andWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$titulo.'%');
                    }if($numero){
                        $consulta->andWhere('p.numero LIKE :numero')->setParameter('numero','%'.$numero.'%');
                    }if($año){
                        $consulta->andWhere('p.fechaPublicacion LIKE :fecha')->setParameter('fecha','%'.$año.'%');
                    }
                    if($tipo){
                        $consulta->andWhere('p.tipoNorma = :tipo')->setParameter('tipo',$tipo);
                    }
        }
        $consultaAux="p.estado = 'Publicada' AND p.publico =1";
        $consulta->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')->andWhere($consultaAux)->orderBy('p.titulo','ASC');
        $query=$consulta->getQuery();
        //dd($query);
        return $query;
    
    }

    //busqueda de los filtros con session
    public function findNormasSession($titulo,$numero,$año,$tipo,$arrayDeNormas,$reparticion,$rol): Query 
    {
        if($rol=="DIG_CONSULTOR"){
            $consultaAux2=" AND p.estado='Publicada'";
        }else{
            $consultaAux2="";
        }
        $cont=0;
        $tam=count($arrayDeNormas);
        
        $consulta=$this->createQueryBuilder('p');
        $consulta1="";
        if($arrayDeNormas){//entra si hay mas de una norma
            //if(count($arrayDeNormas)>1){
                //$consulta->where('p.id = :id');
                for ($i=0; $i < $tam; $i++) {
                    if($i==0){
                        $consulta1.= "(p.id = ".$arrayDeNormas[$i]->getId();
                    //     $consulta->setParameter('id',$arrayDeNormas[$i]);
                    }else{
                        $consulta1.= "OR(p.id = " .$arrayDeNormas[$i]->getId();
                    //     $consulta->orWhere($consulta->expr()->orX(
                    //     'p.id = :id'))->setParameter('id',$arrayDeNormas[$i]);
                    }
                        if($titulo){
                            $consulta1.= " AND p.titulo LIKE '%".$titulo."%'";
                            //$consulta->andWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$titulo.'%');
                        }if($numero){
                            $consulta1.= " AND p.numero LIKE '%".$numero."%'";
                            //$consulta->andWhere('p.numero LIKE :numero')->setParameter('numero','%'.$numero.'%');
                        }if($año){
                            $consulta1.= " AND p.fechaPublicacion LIKE '%".$año."%'" ;
                            //$consulta->andWhere('p.fechaPublicacion LIKE :fecha')->setParameter('fechaPublicacion', '%'.$año.'%');
                        }
                        if($tipo){
                            $consulta1.= " AND p.tipoNorma = ".$tipo;
                            //$consulta->andWhere('p.tipoNorma = :tipo')->setParameter('tipoNorma',$tipo);
                        }
                        $consulta1.= ")";
                }
                
                $consulta=$this->createQueryBuilder('p')->where($consulta1);

            //dd($consulta1);
            //}
        }else{
            
            if($titulo){
                    $consulta->andWhere('p.titulo LIKE :titulo')->setParameter('titulo','%'.$titulo.'%');
                    }if($numero){
                        $consulta->andWhere('p.numero LIKE :numero')->setParameter('numero','%'.$numero.'%');
                    }if($año){
                        $consulta->andWhere('p.fechaPublicacion LIKE :fecha')->setParameter('fecha','%'.$año.'%');
                    }
                    if($tipo){
                        $consulta->andWhere('p.tipoNorma = :tipo')->setParameter('tipo',$tipo);
                    }
        }
        $consultaAux="(tnr.reparticionId='".$reparticion->getId()."'".$consultaAux2.") OR (p.estado = 'Publicada' AND p.publico =1)";
        $consulta->join('App\Entity\TipoNorma','t','WITH','p.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaRol','tr','WITH','tr.tipoNorma = t.id')
        ->join('App\Entity\TipoNormaReparticion','tnr','WITH','tnr.tipoNormaId = tr.tipoNorma')
        ->andWhere($consultaAux)
        //->andWhere("tnr.reparticionId='".$reparticion->getId()."'")
        
        ->orderBy('p.titulo','ASC');
        $query=$consulta->getQuery();
        //dd($query);
        return $query;

    }
    
    public function findNormasEtiqueta($normasEtiquetas): Query
    {
        $tam=count($normasEtiquetas);
        $consulta=$this->createQueryBuilder('p');
        for ($i=0; $i <$tam ; $i++) { 
            $consulta->orWhere('p.id = :id')->setParameter('id',$normasEtiquetas[$i]->getId());
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
