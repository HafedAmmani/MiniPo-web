<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieReclamation
 *
 * @ORM\Table(name="categorie_reclamation")
 * @ORM\Entity
 */
class CategorieReclamation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcatrec", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcatrec;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @return int
     */
    public function getIdcatrec()
    {
        return $this->idcatrec;
    }

    /**
     * @param int $idcatrec
     */
    public function setIdcatrec($idcatrec)
    {
        $this->idcatrec = $idcatrec;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }


}

