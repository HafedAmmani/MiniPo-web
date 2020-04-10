<?php


namespace minipoBundle\Repository;
use Doctrine\ORM\EntityRepository;

class livraisonRepository extends EntityRepository
{
    public function findLivraison(){
        $qb=$this->getEntityManager()->createQuery("select l from minipoBundle:livraison l");
        return $query=$qb->getResult();
    }

    public function findLivreur($id){
        $qb=$this->getEntityManager()->createQuery("select l from minipoBundle:livraison l where l.id=:id ")->setParameter('id', $id);
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
    public function getEmailData($idc,$idliv){
        $qb=$this->getEntityManager()
            ->createQuery("select c,l from minipoBundle:livraison l, minipoBundle:commande c where c.idcmd=:idc and l.idliv=:idliv")
            ->setParameter('idc',$idc)
            ->setParameter('idliv', $idliv);
        return $query=$qb->getResult();

    }

}