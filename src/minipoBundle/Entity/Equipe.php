<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity
 */
class Equipe
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idEq", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ideq;

    /**
     * @var integer
     *
     * @ORM\Column(name="Nombre", type="integer", nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="NomEq", type="string", length=30, nullable=false)
     */
    private $nomeq;

    /**
     * @return int
     */
    public function getIdeq()
    {
        return $this->ideq;
    }

    /**
     * @param int $ideq
     */
    public function setIdeq($ideq)
    {
        $this->ideq = $ideq;
    }

    /**
     * @return int
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param int $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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


}

