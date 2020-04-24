<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class EmployeRepository extends EntityRepository
{
    public function findemploye(){
        $qb = $this->getEntityManager()
            ->createQuery("select c from minipoBundle:User c where c.roles = 'a:1:{i:0;s:12:\"ROLE_EMPLOYE\";}'");
        return $query = $qb->getResult();
    }
    public function findCongeemploye(){
        $qb = $this->getEntityManager()
            ->createQuery("select c from minipoBundle:Conge c where c.etat = 0");
        return $query = $qb->getResult();
    }
    public function findDemandeAccepter(){
        $qb = $this->getEntityManager()
            ->createQuery("select c from minipoBundle:Conge c where c.etat = 1");
        return $query = $qb->getResult();
    }
    public function findemployeenconge(){
        $qb = $this->getEntityManager()
            ->createQuery("select u.firstname, u.lastname, u.email, u.tel, c.datedebut, c.datefin, c.type, c.description FROM minipoBundle:User u, minipoBundle:Conge c WHERE u.id = c.id");
        return $query = $qb->getResult();
    }
    public function recherche(){

    }




}