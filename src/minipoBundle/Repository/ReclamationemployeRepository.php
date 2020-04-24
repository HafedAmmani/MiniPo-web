<?php
namespace minipoBundle\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use minipoBundle\Entity\Reclamationemploye;

class ReclamationemployeRepository extends EntityRepository{




    public function myfindDomaine(){
        $qb = $this->getEntityManager()
            ->createQuery("select c from minipoBundle:Reclamation r where r.etatr = 'traiter'");
        return $query = $qb->getResult();
    }
    public function recherche(){

    }

    public function myfindlike($d){
        $qb= $this->getEntityManager()->createQuery("select r from minipoBundle:Reclamation r where r.etatr like '".$d."%'");
        return $query =$qb->getResult();
    }
    public function findEntitiesByString($str){
        return $this->getEntityManager()
            ->createQuery(
                'SELECT r
                FROM minipoBundle:Reclamation r
                WHERE r.etatr LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }
    public function search($request) {
        //extract($request);
        $qb = $this->createQueryBuilder('r')
            ->join('r.idcatrecemp','c');

        if($request->query->getAlnum('filter')){
            $qb->where('r.etatremp LIKE :keyword or r.objet LIKE :keyword or r.description LIKE :keyword or r.dateremp LIKE :keyword or r.reponse LIKE :keyword')
                ->setParameter('keyword','%'.$request->query->getAlnum('filter').'%');}

        return $qb->getQuery()->getResult();
    }

    public function counter() {
        $qb = $this->createQueryBuilder('p')->select('COUNT(p)');
        try {
            return $qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
        } catch (NonUniqueResultException $e) {
        }
    }

    public function getCities()
    {
        return  $this->fetch("select distinct etatRemp as label from reclamationemploye");
    }
    private function fetch($query)
    {
        try {
            $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        } catch (DBALException $e) {
        }
        $stmt->execute();
        return  $stmt->fetchAll();
    }
}