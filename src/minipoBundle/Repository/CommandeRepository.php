<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CommandeRepository extends EntityRepository
{
    public function myFindCmdByClt($id){
        $qb=$this->getEntityManager()->
        createQuery("select c 
                                      from minipoBundle:Commande c 
                                      where c.id=$id
                                      and (c.etatc='Validee' or c.etatc='Acceptee' or c.etatc='Refusee')");
        return $query=$qb->getResult();

    }
    public function myFindPanierByClt($id){
        $qb=$this->getEntityManager()->
        createQuery("select c 
                          from minipoBundle:Commande c 
                          where c.id=$id
                          and c.etatc='Non Validee'");
        return $query=$qb->getOneOrNullResult();

    }

    public function myFindTotalCmd($idCmd){
        $qb=$this->getEntityManager()->
        createQuery("select SUM(lc.subtotal)  
                          from minipoBundle:Lignecommande lc
                          where lc.idcmd=$idCmd " );
        return $query=$qb->getSingleScalarResult();

    }

    public function myFindAllCmd(){
        $qb=$this->getEntityManager()->
        createQuery("select c 
                          from minipoBundle:Commande c 
                          where c.etatc='Validee' or c.etatc='Acceptee' or c.etatc='Refusee'");
        return $query=$qb->getResult();

    }

    public function myFindCmdByLiv($idl){
        $qb=$this->getEntityManager()->
        createQuery("select c 
                          from minipoBundle:Commande c , minipoBundle:Livraison l
                          where c.idcmd=l.idcmd
                          and l.idliv=$idl");

        return $query=$qb->getOneOrNullResult();

    }
}