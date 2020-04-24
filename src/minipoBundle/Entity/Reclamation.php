<?php

namespace minipoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="idcatrec", columns={"idcatrec"}), @ORM\Index(name="idcmd", columns={"idcmd"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idr", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=5,minMessage="vous devez inserer une description s'il vous plait!")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dater", type="date", nullable=false)
     */
    private $dater;

    /**
     * @var string
     *
     * @ORM\Column(name="Reponse", type="string", length=255, nullable=true)
     */
    private $reponse;

    /**
     * @var string
     *
     * @ORM\Column(name="etatr", type="string", length=20, nullable=false)
     */
    private $etatr;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=500, nullable=true)
     * @Assert\File(maxSize="500k", mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/GIF"})
     *
     */
    private $image;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;

    /**
     * @var \CategorieReclamation
     *
     * @ORM\ManyToOne(targetEntity="CategorieReclamation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcatrec", referencedColumnName="idcatrec")
     * })
     */
    private $idcatrec;

    /**
     * Reclamation constructor.
     * @param \DateTime $dater
     */
    public function __construct()
    {
        $this->dater =  new \DateTime('now');
    }

    /**
     * @return int
     */
    public function getIdr()
    {
        return $this->idr;
    }

    /**
     * @param int $idr
     */
    public function setIdr($idr)
    {
        $this->idr = $idr;
    }

    /**
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * @param string $objet
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
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
     * @return \DateTime
     */
    public function getDater()
    {
        return $this->dater;
    }

    /**
     * @param \DateTime $dater
     */
    public function setDater($dater)
    {
        $this->dater = $dater;
    }

    /**
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * @param string $reponse
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
    }

    /**
     * @return string
     */
    public function getEtatr()
    {
        return $this->etatr;
    }

    /**
     * @param string $etatr
     */
    public function setEtatr($etatr)
    {
        $this->etatr = $etatr;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
     * @return \CategorieReclamation
     */
    public function getIdcatrec()
    {
        return $this->idcatrec;
    }

    /**
     * @param \CategorieReclamation $idcatrec
     */
    public function setIdcatrec($idcatrec)
    {
        $this->idcatrec = $idcatrec;
    }


}

