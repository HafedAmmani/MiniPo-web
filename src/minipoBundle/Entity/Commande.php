<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_clt", columns={"id"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcmd", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcmd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datec", type="date", nullable=false)
     */
    private $datec;

    /**
     * @var string
     *
     * @ORM\Column(name="etatc", type="string", length=30, nullable=false)
     */
    private $etatc;

    /**
     * @var string
     *
     * @ORM\Column(name="refC", type="string", length=255, nullable=true)
     */
    private $refc;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=false)
     */
    private $total;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @return int
     */
    public function getIdcmd()
    {
        return $this->idcmd;
    }

    /**
     * @param int $idcmd
     */
    public function setIdcmd($idcmd)
    {
        $this->idcmd = $idcmd;
    }

    /**
     * @return \DateTime
     */
    public function getDatec()
    {
        return $this->datec;
    }

    /**
     * @param \DateTime $datec
     */
    public function setDatec($datec)
    {
        $this->datec = $datec;
    }

    /**
     * @return string
     */
    public function getEtatc()
    {
        return $this->etatc;
    }

    /**
     * @param string $etatc
     */
    public function setEtatc($etatc)
    {
        $this->etatc = $etatc;
    }

    /**
     * @return string
     */
    public function getRefc()
    {
        return $this->refc;
    }

    /**
     * @param string $refc
     */
    public function setRefc($refc)
    {
        $this->refc = $refc;
    }

    /**
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @return \User
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \User $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {

        return strval( $this->getIdcmd() );
    }


}

