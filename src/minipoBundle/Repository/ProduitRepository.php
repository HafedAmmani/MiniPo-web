<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProduitRepository extends EntityRepository
{

    public function myFindProduitByPanier($idPan,$idProd){
        $qb=$this->getEntityManager()->
        createQuery("select p 
                     from  minipoBundle:Produit p , minipoBundle:Lignecommande lc 
                     where p.idprod=lc.idprod
                     and lc.idcmd=$idPan
                     and lc.idprod=$idProd 
                     ");

        return $query=$qb->getOneOrNullResult();
    }

    public function myFindProduitByPanier1($idPan,$idProd){
        $qb=$this->getEntityManager()->
        createQuery("select p 
                     from  minipoBundle:Produit p , minipoBundle:Lignecommande lc 
                     where p.idprod=lc.idprod
                     and lc.idcmd=$idPan
                     and lc.idprod=$idProd 
                     ");

        return $query=$qb->getSingleResult();
    }

}