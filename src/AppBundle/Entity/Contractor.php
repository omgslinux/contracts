<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Contractor
 *
 * @ORM\Table(name="contractors")
 * @ORM\Entity
 */
class Contractor
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
     * @ORM\Column(type="string", length=16, unique=true)
     */
    private $budgetid;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="contractor")
     */
    private $contracts;


    public function __construct()
    {
        $this->contracts=new ArrayCollection();
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
     * @return Contractor
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
     * Set budgetid
     *
     * @param string $budgetid
     *
     * @return Contractor
     */
    public function setBudgetid($text)
    {
        $this->budgetid = $text;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getBudgetid()
    {
        return $this->cbudgetid;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
