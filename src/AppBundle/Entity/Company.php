<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $companyName;


    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    private $nif;

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Company
     */
    public function setCompanyName($value)
    {
        $this->companyName = $value;
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

    /**
     * Set nif
     *
     * @param string $nif
     * @return Company
     */
    public function setNIF($value)
    {
        $this->nif = $value;
        return $this;
    }

    public function __toString()
    {
        return $this->getCompanyName();
    }
}
