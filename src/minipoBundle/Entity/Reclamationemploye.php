<?php

namespace minipoBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reclamationemploye
 *
 * @ORM\Table(name="reclamationemploye", indexes={@ORM\Index(name="id", columns={"id"}), @ORM\Index(name="idcatrec", columns={"idcatrec"})})
 * @ORM\Entity(repositoryClass="minipoBundle\Repository\ReclamationemployeRepository")
 */
class Reclamationemploye
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idRemp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idremp;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=10,minMessage="vous devez inserer une description s'il vous plait!")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateRemp", type="date", nullable=false)
     */
    private $dateremp;

    /**
     * @var string
     *
     * @ORM\Column(name="etatRemp", type="string", length=255, nullable=false)
     */
    private $etatremp;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255, nullable=false)
     *  @Assert\NotBlank
     * @Assert\Length(min=5)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="reponse", type="string", length=500, nullable=false)
     */
    private $reponse;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=500, nullable=true)
     * @Assert\File(maxSize="500k", mimeTypes={"image/jpeg", "image/jpg", "image/png", "image/GIF"})
     */
    private $image;

    /**
     * @var \CategorieReclamationEmp
     *
     * @ORM\ManyToOne(targetEntity="CategorieReclamationEmp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idcatrecemp", referencedColumnName="idcatrecemp")
     * })
     */
    private $idcatrecemp;



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
     * Reclamationemploye constructor.
     * @param \DateTime $dateremp
     */
    public function __construct()
    {
        $this->dateremp = new \DateTime('now');
    }

    public function setDateRemp(\DateTime $param)
    {
    }

    /**
     * @return int
     */
    public function getIdremp()
    {
        return $this->idremp;
    }

    /**
     * @param int $idremp
     */
    public function setIdremp($idremp)
    {
        $this->idremp = $idremp;
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
     * @return string
     */
    public function getEtatremp()
    {
        return $this->etatremp;
    }

    /**
     * @param string $etatremp
     */
    public function setEtatremp($etatremp)
    {
        $this->etatremp = $etatremp;
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
     * @return \CategorieReclamationEmp|int
     */
    public function  getIdcatrecemp()
    {
        return  $this->idcatrecemp;
    }

    /**
     * @param \CategorieReclamationEmp $idcatrecemp
     */
    public function setIdcatrecemp($idcatrecemp)
    {
        $this->idcatrecemp = $idcatrecemp;
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
     * @return \DateTime
     */
    public function getDateremp()
    {
        return $this->dateremp;
    }


}

