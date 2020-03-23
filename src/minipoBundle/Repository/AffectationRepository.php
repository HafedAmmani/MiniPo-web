<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class AffectationRepository extends EntityRepository
{
    public function findequipe(){
        $qb = $this->getEntityManager()
            ->createQuery("select * from minipoBundle:Equipe");
        return $query = $qb->getResult();
    }
    public function recherche(){

    }




}