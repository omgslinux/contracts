<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Laboratory
 *
 * @ORM\Table(name="laboratories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LaboratoryRepository")
 */
class Laboratory
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
     * @ORM\Column(type="string", length=10, unique=true)
     */
    private $nif;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Medicament", mappedBy="laboratory")
     */
    private $medicaments;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Contract", mappedBy="company")
     */
    private $contracts;


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
     * @return Laboratory
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
     * Set nif
     *
     * @param string $nif
     *
     * @return Laboratory
     */
    public function setNIF($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getNIF()
    {
        return $this->nif;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
