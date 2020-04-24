<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facture
 *
 * @ORM\Table(name="facture", indexes={@ORM\Index(name="idcmd", columns={"idcmd"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\FactureRepository")
 */
class Facture
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idfact", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idfact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datef", type="date", nullable=false)
     */
    private $datef;

    /**
     * @var string
     *
     * @ORM\Column(name="etatf", type="string", length=30, nullable=false)
     */
    private $etatf;

    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcmd", referencedColumnName="idcmd")
     * })
     */
    private $idcmd;

    /**
     * @return int
     */
    public function getIdfact()
    {
        return $this->idfact;
    }

    /**
     * @param int $idfact
     */
    public function setIdfact($idfact)
    {
        $this->idfact = $idfact;
    }

    /**
     * @return \DateTime
     */
    public function getDatef()
    {
        return $this->datef;
    }

    /**
     * @param \DateTime $datef
     */
    public function setDatef($datef)
    {
        $this->datef = $datef;
    }

    /**
     * @return string
     */
    public function getEtatf()
    {
        return $this->etatf;
    }

    /**
     * @param string $etatf
     */
    public function setEtatf($etatf)
    {
        $this->etatf = $etatf;
    }

    /**
     * @return \Commande
     */
    public function getIdcmd()
    {
        return $this->idcmd;
    }

    /**
     * @param \Commande $idcmd
     */
    public function setIdcmd($idcmd)
    {
        $this->idcmd = $idcmd;
    }


}

