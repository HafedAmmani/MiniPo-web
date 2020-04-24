<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="fk_idl", columns={"id"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\livraisonRepository")
 */

class Livraison
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idliv", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idliv;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=50, nullable=false)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="etatl", type="enumetat",nullable=false, options={"default":"non livree"})
     */
    private $etatl="non livree";
    /**
     * @var \Commande
     *
     * @ORM\ManyToOne(targetEntity="Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idc", referencedColumnName="idcmd")
     * })
     */
    private $idc;

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
     * @var string
     * @Assert\NotBlank(message="Le champ date est obligatoire")
     * @Assert\Regex("/^\d{1,2}\/\d{1,2}\/\d{4}$/",message="Le champ date n'est pas conforme (exp: dd/mm/yyyy)")
     * @ORM\Column(name="dateliv", type="string", length=255, nullable=false)
     */
    private $dateliv;

    /**
     * @var string
     *
     * @ORM\Column(name="matriculeL", type="string", length=255, nullable=true)
     */
    private $matriculel;

    /**
     * @return int
     */
    public function getIdliv()
    {
        return $this->idliv;
    }

    /**
     * @param int $idliv
     */
    public function setIdliv($idliv)
    {
        $this->idliv = $idliv;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getEtatl()
    {
        return $this->etatl;
    }

    /**
     * @param string $etatl
     */
    public function setEtatl($etatl)
    {
        $this->etatl = $etatl;
    }

    /**
     * @return \Commande
     */
    public function getIdc()
    {
        return $this->idc;
    }

    /**
     * @param \Commande $idc
     */
    public function setIdc($idc)
    {
        $this->idc = $idc;
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

    /**
     * @return string
     */
    public function getDateliv()
    {
        return $this->dateliv;
    }

    /**
     * @param string $dateliv
     */
    public function setDateliv($dateliv)
    {
        $this->dateliv = $dateliv;
    }

    /**
     * @return string
     */
    public function getMatriculel()
    {
        return $this->matriculel;
    }

    /**
     * @param string $matriculel
     */
    public function setMatriculel($matriculel)
    {
        $this->matriculel = $matriculel;
    }







}

