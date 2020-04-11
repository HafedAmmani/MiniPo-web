<?php


namespace minipoBundle\Repository;


use Doctrine\ORM\EntityRepository;

class BlogRepository extends EntityRepository{

    public function findEntitiesByString($str){

        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM minipoBundle:Articles p
                WHERE p.titre LIKE :str'
            )
            ->setParameter('str', '%'.$str.'%')
            ->getResult();
    }



    /**
     * get one by id
     *
     * @param integer $id
     *
     * @return object or null
     */
    public function findPostByid($id)
    {
        try {
            return $this->getEntityManager()
                ->createQuery(
                    "SELECT p
                FROM minipoBundle:Articles
                p WHERE p.id =:id"
                )
                ->setParameter('id', $id)
                ->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }
    }
    public function findcomment($id){
        $qb = $this->getEntityManager()
            ->createQuery("SELECT c From minipoBundle:commentaire c WHERE c.ida=$id");
        return $query = $qb->getResult();
    }
    public function findcommentrep($id){
        $qb = $this->getEntityManager()
            ->createQuery("SELECT c From minipoBundle:commentaireRep c WHERE c.idcom=$id");
        return $query = $qb->getResult();
    }
    public function deletcomment($idcom){
        $qb = $this->getEntityManager()
            ->createQuery("DELETE  From minipoBundle:commentaire c WHERE c.idcom=$idcom");
        return $query = $qb->getResult();
    }


}
