<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class FactureRepository extends EntityRepository
{

    public function myFindFactByClt($id){
        $qb=$this->getEntityManager()->
        createQuery("select f 
                          from minipoBundle:Facture f , minipoBundle:Commande c , minipoBundle:User u
                          where f.idcmd=c.idcmd
                          and c.id=u.id
                          and u.id=$id ");
        return $query=$qb->getResult();

    }

    public function myFindFactByCmd($idCmd){
        $qb=$this->getEntityManager()->
        createQuery("select f 
                          from minipoBundle:Facture f , minipoBundle:Commande c
                          where f.idcmd=c.idcmd
                          and c.idcmd=$idCmd ");

        return $query=$qb->getOneOrNullResult();

    }

}