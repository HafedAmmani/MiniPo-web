<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class LigneCommandeRepository extends EntityRepository
{
    public function myFindPanier($id){
        $qb=$this->getEntityManager()->
        createQuery("select lc 
                     from minipoBundle:Lignecommande lc , minipoBundle:Commande c,minipoBundle:User u
                     where lc.idcmd=c.idcmd 
                     and c.id=u.id
                     and u.id=$id
                     and c.etatc='Non Validee'");

        return $query=$qb->getResult();

    }


    public function myFindQte($idprod){
        $qb=$this->getEntityManager()->
        createQuery("select SUM(lc.qte) 
                     from minipoBundle:Lignecommande lc 
                     where lc.idprod=1");

        return $query=$qb->getSingleScalarResult();

    }

    public function myFindLcByProd($idpp,$idPan){
        $qb=$this->getEntityManager()->
        createQuery("select lc 
                     from  minipoBundle:Lignecommande lc ,minipoBundle:Produit p , minipoBundle:Commande c
                     where lc.idprod=p.idprod
                     and lc.idcmd=c.idcmd
                     and c.idcmd=$idPan
                     and p.idprod=$idpp");

        return $query=$qb->getSingleResult();

    }

    public function myFindMaxLcByCmd($idCmd){
        //$j=strval( $idCmd );
        $qb=$this->getEntityManager()->
        createQuery("select count (lc.idlc) 
                     from  minipoBundle:Lignecommande lc 
                     where lc.idcmd=$idCmd");
        return $query=$qb->getOneOrNullResult();

    }

    public function myFindLcByCmd($idPan){
        $qb=$this->getEntityManager()->
        createQuery("select lc 
                     from  minipoBundle:Lignecommande lc 
                     where lc.idcmd=$idPan
                     ");

        return $query=$qb->getResult();

    }
    public function myFindLcByFact($idFact,$idCmd){
        $qb=$this->getEntityManager()->
        createQuery("select lc 
                     from  minipoBundle:Lignecommande lc,minipoBundle:Facture f
                     where lc.idcmd=$idCmd
                     and f.idfact=$idFact
                     ");
        return $query=$qb->getResult();

    }






}