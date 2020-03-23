<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conge
 *
 * @ORM\Table(name="conge", indexes={@ORM\Index(name="id", columns={"id"})})
 * @ORM\Entity
 */
class Conge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcon", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcon;

    /**
     * @var string
     *
     * @ORM\Column(name="datedebut", type="string", length=150, nullable=false)
     */
    private $datedebut;

    /**
     * @var string
     *
     * @ORM\Column(name="datefin", type="string", length=150, nullable=false)
     */
    private $datefin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=false)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean", nullable=false)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrjrs", type="integer", nullable=false)
     */
    private $nbrjrs;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=150, nullable=false)
     */
    private $type;

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
    public function getIdcon()
    {
        return $this->idcon;
    }

    /**
     * @param int $idcon
     */
    public function setIdcon($idcon)
    {
        $this->idcon = $idcon;
    }

    /**
     * @return string
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * @param string $datedebut
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;
    }

    /**
     * @return string
     */
    public function getDatefin()
    {
        return $this->datefin;
    }

    /**
     * @param string $datefin
     */
    public function setDatefin($datefin)
    {
        $this->datefin = $datefin;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isEtat()
    {
        return $this->etat;
    }

    /**
     * @param bool $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return int
     */
    public function getNbrjrs()
    {
        return $this->nbrjrs;
    }

    /**
     * @param int $nbrjrs
     */
    public function setNbrjrs($nbrjrs)
    {
        $this->nbrjrs = $nbrjrs;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
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


}

