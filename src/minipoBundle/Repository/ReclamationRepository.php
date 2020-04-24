<?php
namespace minipoBundle\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use minipoBundle\Entity\Reclamation;

class ReclamationRepository extends EntityRepository{




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
    public function search($searchParam) {
        extract($searchParam);
        $qb = $this->createQueryBuilder('p');

        if(!empty($keyword))
            $qb->andWhere('  r.etatRemp like :keyword or r.objet like :keyword or r.description like :keyword')
                ->setParameter('keyword', '%'.$keyword.'%');
        if(!empty($ids))
            $qb->andWhere('r.id in (:id)')->setParameter('id', $ids);
        if(!empty($idRemp))
            $qb->andWhere('r.idRemp = :idRemp')->setParameter('idRemp', $idRemp);
        if(!empty($idcatrec))
            $qb->andWhere('r.idcatrec = :idcatrec')->setParameter('idcatrec', $idcatrec);
        if(!empty($objet))
            $qb->andWhere('p.objet = :objet')->setParameter('objet', $objet);
        if(!empty($dateRemp ))
            $qb->andWhere('r.dateRemp  > :dateRemp ')->setParameter('dateRemp ', $dateRemp );



        return new Paginator($qb->getQuery());
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