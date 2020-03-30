<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class EmployeRepository extends EntityRepository
{
    public function findemploye(){
        $qb = $this->getEntityManager()
            ->createQuery("select c from minipoBundle:User c where c.roles = 'a:1:{i:0;s:7:\"EMPLOYE\";}'");
        return $query = $qb->getResult();
    }

    public function recherche(){

    }




}