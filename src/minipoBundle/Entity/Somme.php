<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 *
 * @ORM\Table(name="somme")
 * @ORM\Entity
 */
class Somme
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idsom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idsom;

    /**
     * @var integer
     *
     * @ORM\Column(name="Som", type="integer", nullable=false)
     */
    private $som;

    /**
     * @var string
     *
     * @ORM\Column(name="Mois", type="string", length=30, nullable=false)
     */
    private $mois;

    /**
     * @return int
     */
    public function getIdsom()
    {
        return $this->idsom;
    }

    /**
     * @param int $idsom
     */
    public function setIdsom($idsom)
    {
        $this->idsom = $idsom;
    }

    /**
     * @return int
     */
    public function getSom()
    {
        return $this->som;
    }

    /**
     * @param int $som
     */
    public function setSom($som)
    {
        $this->som = $som;
    }

    /**
     * @return string
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * @param string $mois
     */
    public function setMois($mois)
    {
        $this->mois = $mois;
    }



}

