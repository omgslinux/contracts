<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medicament;
use AppBundle\Entity\Laboratory;
use AppBundle\Entity\ActivePrinciple;

/**
 * Medicament
 *
 * @ORM\Table(name="medicaments")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MedicamentRepository")
 */
class Medicament
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="Code", type="string", length=8, nullable=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(name="Situation", type="smallint", nullable=true)
     */
    private $situation;

    /**
     * @var Laboratory
     *
     * @ORM\ManyToOne(targetEntity="Laboratory", inversedBy="medicaments")
     */
    private $laboratory;

    /**
     * @var activePrinciple
     *
     * @ORM\ManyToOne(targetEntity="ActivePrinciple", inversedBy="medicaments")
     * @ORM\OrderBy({"name"="ASC"})
     */
    private $activePrinciple;

    /**
     * @var bool
     *
     * @ORM\Column(name="BillSNS", type="boolean", nullable=true)
     */
    private $billSNS;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Meds
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Meds
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set situation
     *
     * @param integer $situation
     *
     * @return Meds
     */
    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get situation
     *
     * @return int
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set laboratory
     *
     * @param Laboratory $laboratory
     *
     * @return Meds
     */
    public function setLaboratory(Laboratory $laboratory)
    {
        $this->laboratory = $laboratory;

        return $this;
    }

    /**
     * Get laboratory
     *
     * @return Laboratory
     */
    public function getLaboratory()
    {
        return $this->laboratory;
    }

    /**
     * Set activeP
     *
     * @param \stdClass $activeP
     *
     * @return Meds
     */
    public function setActivePrinciple(ActivePrinciple $value)
    {
        $this->activePrinciple = $value;

        return $this;
    }

    /**
     * Get activeP
     *
     * @return \stdClass
     */
    public function getActivePrinciple()
    {
        return $this->activePrinciple;
    }

    /**
     * Set billSNS
     *
     * @param boolean $billSNS
     *
     * @return Meds
     */
    public function setBillSNS($billSNS)
    {
        $this->billSNS = $billSNS;

        return $this;
    }

    /**
     * Get billSNS
     *
     * @return bool
     */
    public function getBillSNS()
    {
        return $this->billSNS;
    }
}
