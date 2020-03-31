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
                                      and c.etatc='Validee' or c.etatc='Acceptee' ");
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
}