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

    public function searchLivraison($id, $etatl, $destination){
        if($etatl === "all")
            $qb=$this->getEntityManager()
                ->createQuery("select l from minipoBundle:livraison l where l.id=:id and l.destination LIKE :destination")
                ->setParameter('id', $id)
                ->setParameter('destination', $destination);
        else
            $qb=$this->getEntityManager()
                ->createQuery("select l from minipoBundle:livraison l where l.id=:id and l.etatl=:etatl and l.destination LIKE :destination")
                ->setParameter('etatl', $etatl)
                ->setParameter('id', $id)
                ->setParameter('destination', $destination);
        return $query=$qb->getResult();
    }


}