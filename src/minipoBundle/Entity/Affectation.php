<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Affectation
 *
 * @ORM\Table(name="affectation", indexes={@ORM\Index(name="idemp", columns={"id"}), @ORM\Index(name="idEq", columns={"idEq"})})
 * @ORM\Entity
 */
class Affectation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idaffect", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idaffect;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=150, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="NomEq", type="string", length=150, nullable=false)
     */
    private $nomeq;

    /**
     * @var \Equipe
     *
     * @ORM\ManyToOne(targetEntity="Equipe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idEq", referencedColumnName="idEq")
     * })
     */
    private $ideq;

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
    public function getIdaffect()
    {
        return $this->idaffect;
    }

    /**
     * @param int $idaffect
     */
    public function setIdaffect($idaffect)
    {
        $this->idaffect = $idaffect;
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

    /**
     * @return string
     */
    public function getNomeq()
    {
        return $this->nomeq;
    }

    /**
     * @param string $nomeq
     */
    public function setNomeq($nomeq)
    {
        $this->nomeq = $nomeq;
    }

    /**
     * @return \Equipe
     */
    public function getIdeq()
    {
        return $this->ideq;
    }

    /**
     * @param \Equipe $ideq
     */
    public function setIdeq($ideq)
    {
        $this->ideq = $ideq;
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

