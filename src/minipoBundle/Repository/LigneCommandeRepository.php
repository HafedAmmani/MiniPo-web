<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class LigneCommandeRepository extends EntityRepository
{
    public function myFindPanier($id){
        $qb=$this->getEntityManager()->
        /*createQuery("select lc
                     from minipoBundle:Lignecommande lc
                     join minipoBundle:Commande c
                     on lc.idcmd=c.idcmd
                     join minipoBundle:User u
                     on c.id=u.id
                     where u.id=$id
                     and c.etatc='Non Validee'");*/
        createQuery("select lc 
                     from minipoBundle:Lignecommande lc , minipoBundle:Commande c,minipoBundle:User u
                     where lc.idcmd=c.idcmd 
                     and c.id=u.id
                     and u.id=$id
                     and c.etatc='Non Validee'");

        return $query=$qb->getResult();

    }

}