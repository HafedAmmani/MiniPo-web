<?php


namespace minipoBundle\Repository;
use Doctrine\ORM\EntityRepository;

class livraisonRepository extends EntityRepository
{
    public function findLivraison(){
        $qb=$this->getEntityManager()->createQuery("select l from minipoBundle:livraison l");
        return $query=$qb->getResult();
    }

    public function findLivreur(){
        $qb=$this->getEntityManager()->createQuery("select l.matriculel,l.destination,l.etatl,l.dateliv from minipoBundle:livraison l where l.id=54 ");
        return $query=$qb->getResult();
    }


}