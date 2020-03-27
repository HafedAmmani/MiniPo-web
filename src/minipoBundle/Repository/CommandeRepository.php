<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CommandeRepository extends EntityRepository
{
    public function myFindCmdByClt(){

        $qb=$this->getEntityManager()->
        createQuery("SELECT c FROM minipoBundle:Commande c 
                          WHERE c.id=45
                          AND c.etatc='Validee' OR c.etatc='Acceptee' ");
        return $query=$qb->getResult();

    }


}