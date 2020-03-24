<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lignecommande
 *
 * @ORM\Table(name="lignecommande", indexes={@ORM\Index(name="idcmd", columns={"idcmd"}), @ORM\Index(name="idprod", columns={"idprod"})})
 * @ORM\Entity
 */
class Lignecommande
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idlc", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlc;

    /**
     * @var integer
     *
     * @ORM\Column(name="qte", type="integer", nullable=false)
     */
    private $qte;

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
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idprod", referencedColumnName="idprod")
     * })
     */
    private $idprod;

    /**
     * @return int
     */
    public function getIdlc()
    {
        return $this->idlc;
    }

    /**
     * @param int $idlc
     */
    public function setIdlc($idlc)
    {
        $this->idlc = $idlc;
    }

    /**
     * @return int
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * @param int $qte
     */
    public function setQte($qte)
    {
        $this->qte = $qte;
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

    /**
     * @return \Produit
     */
    public function getIdprod()
    {
        return $this->idprod;
    }

    /**
     * @param \Produit $idprod
     */
    public function setIdprod($idprod)
    {
        $this->idprod = $idprod;
    }


}
