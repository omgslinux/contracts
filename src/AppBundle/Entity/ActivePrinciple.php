<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Medicament;
use AppBundle\Entity\Laboratory;

/**
 * ActivePrinciple
 *
 * @ORM\Table(name="active_principle")
 */
class ActivePrinciple
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ActivePrinciple" nullable=true)
     */
    private $parent;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
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
     * @return ActivePrinciple
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
     * @return ActivePrinciple
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
     * Set parent
     *
     * @param ActivePrinciple $parent
     *
     * @return ActivePrinciple
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
     * @return ActivePrinciple
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
     * @return ActivePrinciple
     */
    public function setActiveP($activeP)
    {
        $this->activeP = $activeP;

        return $this;
    }

    /**
     * Get activeP
     *
     * @return \stdClass
     */
    public function getActiveP()
    {
        return $this->activeP;
    }

    /**
     * Set billSNS
     *
     * @param boolean $billSNS
     *
     * @return ActivePrinciple
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
