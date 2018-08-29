<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Medicament;
use AppBundle\Entity\Laboratory;
use AppBundle\Entity\ActivePrinciple;

/**
 * ActivePrinciple
 *
 * @ORM\Table(name="active_principles")
 * @ORM\Entity
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16, nullable=true, unique=true)
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ActivePrinciple")
     */
    private $parent;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Medicament", mappedBy="activePrinciple")
     */
    private $medicaments;


    public function __construct()
    {
        $this->medicaments=new ArrayCollection();
    }

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
    public function setParent(ActivePrinciple $value)
    {
        $this->parent = $value;

        return $this;
    }

    /**
     * Get parent
     *
     * @return ActivePrinciple
     */
    public function getParent()
    {
        return $this->parent;
    }

    public function __toString()
    {
        return $this->getName() . ' (' . $this->getCode() . ")";
    }
}
