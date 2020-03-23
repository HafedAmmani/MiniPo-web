<?php

namespace minipoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table(name="report")
 * @ORM\Entity
 */
class Report
{
    /**
     * @var string
     *
     * @ORM\Column(name="report_name", type="string", length=150, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reportName;

    /**
     * @var string
     *
     * @ORM\Column(name="report_jasper", type="blob", length=65535, nullable=false)
     */
    private $reportJasper;


}

