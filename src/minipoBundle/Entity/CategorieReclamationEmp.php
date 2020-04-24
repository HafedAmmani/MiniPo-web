<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieReclamationEmp
 *
 * @ORM\Table(name="categorie_reclamationemp")
 * @ORM\Entity
 */
class CategorieReclamationEmp
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcatrecemp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcatrecemp;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @return int
     */
    public function getIdcatrecemp()
    {
        return $this->idcatrecemp;
    }

    /**
     * @param int $idcatrecemp
     */
    public function setIdcatrecemp($idcatrecemp)
    {
        $this->idcatrecemp = $idcatrecemp;
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

