<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommentaireRep
 *
 * @ORM\Table(name="commentaireRep", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="idA", columns={"idA"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\BlogRepository")
 */
class CommentaireRep
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcomRep", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcomRep;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionRep", type="string", length=1000, nullable=false)
     */
    private $descriptionRep;


    /**
     * @var \Articles
     *
     * @ORM\ManyToOne(targetEntity="Articles")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idA", referencedColumnName="idA")
     * })
     */
    private $ida;

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
     * @var \Commentaire
     *
     * @ORM\ManyToOne(targetEntity="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcom", referencedColumnName="idcom")
     * })
     */
    private $idcom;


    /**
     * @var string
     *
     * @ORM\Column(name="Lastname", type="string", length=255, nullable=false)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="Firstname", type="string", length=255, nullable=false)
     */
    protected $firstname;

    /**
     * @return int
     */
    public function getIdcomRep()
    {
        return $this->idcom;
    }

    /**
     * @param int $idcomRep
     */
    public function setIdcomRep($idcomRep)
    {
        $this->idcomRep = $idcomRep;
    }

    /**
     * @return string
     */
    public function getDescriptionRep()
    {
        return $this->descriptionRep;
    }

    /**
     * @param string $descriptionRep
     */
    public function setDescriptionRep($descriptionRep)
    {
        $this->descriptionRep = $descriptionRep;
    }

    /**
     * @return \Articles
     */
    public function getIda()
    {
        return $this->ida;
    }

    /**
     * @return \Commentaire
     */
    public function getIdcom()
    {
        return $this->idcom;
    }

    /**
     * @param \Commentaire $idcom
     */
    public function setIdcom($idcom)
    {
        $this->idcom = $idcom;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }
    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @param \Articles $ida
     */
    public function setIda($ida)
    {
        $this->ida = $ida;
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

