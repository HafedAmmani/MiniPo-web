<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="fk_idl", columns={"id"})})
 * @ORM\Entity
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
     * @ORM\Column(name="dateliv", type="string", length=255, nullable=false)
     */
    private $dateliv;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=50, nullable=false)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="etatl", type="string", length=30, nullable=false)
     */
    private $etatl;

    /**
     * @var string
     *
     * @ORM\Column(name="matriculeL", type="string", length=255, nullable=false)
     */
    private $matriculel;

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

