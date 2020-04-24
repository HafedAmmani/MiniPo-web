<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="idA", columns={"idA"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\BlogRepository")
 */
class Commentaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idcom", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;


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
    public function getIdcom()
    {
        return $this->idcom;
    }

    /**
     * @param int $idcom
     */
    public function setIdcom($idcom)
    {
        $this->idcom = $idcom;
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
     * @return \Articles
     */
    public function getIda()
    {
        return $this->ida;
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

