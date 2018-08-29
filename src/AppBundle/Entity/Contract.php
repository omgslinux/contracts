<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\AwardType;
use AppBundle\Entity\ContractType;
use AppBundle\Entity\ContractDescription;

/**
 * Contract
 *
 * @ORM\Table(name="contracts")
 * @ORM\Entity
 */
class Contract
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
    * @var date
    *
    * @ORM\Column(type="date")
    */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $reference;

    /**
    * @var AwardType
    *
    * @ORM\ManyToOne(targetEntity="AwardType", inversedBy="contracts")
    */
    private $awardType;

    /**
     * @var ContractDescription
     *
     * @ORM\ManyToOne(targetEntity="ContractDescription", inversedBy="contracts")
     */
    private $contractDescription;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    private $expediente;

    /**
     * @var ContractType
     *
     * @ORM\ManyToOne(targetEntity="ContractType", inversedBy="contracts")
     */
    private $contractType;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Contractor", inversedBy="contracts")
     */
    private $contractor;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="contracts")
     */
    private $company;

    /**
     * @var decimal
     *
     * @ORM\Column(type="decimal", length=8, scale=2)
     */
    private $withIVA;

    /**
     * @var decimal
     *
     * @ORM\Column(type="decimal", length=8, scale=2)
     */
    private $withoutIVA;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32)
     */
    private $CID;


    public function __construct()
    {
        $this->contractDescription = 0;
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
     * @return Contract
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
     * Set company
     *
     * @param Company $company
     *
     * @return Contract
     */
    public function setCompany(Company $name)
    {
        $this->company = $name;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set contractor
     *
     * @param Contractor $contractor
     *
     * @return Contract
     */
    public function setContractor(Contractor $contractor)
    {
        $this->contractor = $contractor;

        return $this;
    }

    /**
     * Get contractor
     *
     * @return Contractor
     */
    public function getContractor()
    {
        return $this->contractor;
    }

    /**
     * Set awardType
     *
     * @param Company $awardType
     *
     * @return Contract
     */
    public function setAwardType(AwardType $value)
    {
        $this->awardType = $value;

        return $this;
    }

    /**
     * Get awardType
     *
     * @return Company
     */
    public function getAwardType()
    {
        return $this->awardType;
    }

    /**
     * Set ContractDescription
     *
     * @param ContractDescription $contractDescription
     *
     * @return Contract
     */
    public function setContractDescription(ContractDescription $value)
    {
        $this->contractDescription = $value;

        return $this;
    }

    /**
     * Get contractDescription
     *
     * @return Company
     */
    public function getContractDescription()
    {
        return $this->contractDescription;
    }

    /**
     * Set contractType
     *
     * @param Company $contractType
     *
     * @return Contract
     */
    public function setContractType(ContractType $value)
    {
        $this->contractType = $value;

        return $this;
    }

    /**
     * Get contractType
     *
     * @return Company
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Contract
     */
    public function setReference($name)
    {
        $this->reference = $name;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Contract
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set expediente
     *
     * @param string $expediente
     *
     * @return Contract
     */
    public function setExpediente($expediente)
    {
        $this->expediente = $expediente;

        return $this;
    }

    /**
     * Get expediente
     *
     * @return string
     */
    public function getExpediente()
    {
        return $this->expediente;
    }

    /**
     * Set date
     *
     * @param \Date $date
     *
     * @return Contract
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set withIVA
     *
     * @param decimal $withIVA
     *
     * @return Contract
     */
    public function setWithIVA($number)
    {
        $this->withIVA = $number;

        return $this;
    }

    /**
     * Get withIVA
     *
     * @return decimal
     */
    public function getWithIVA()
    {
        return $this->withIVA;
    }

    /**
     * Set withoutIVA
     *
     * @param decimal $withoutIVA
     *
     * @return Contract
     */
    public function setWithoutIVA($number)
    {
        $this->withoutIVA = $number;

        return $this;
    }

    /**
     * Get withoutIVA
     *
     * @return decimal
     */
    public function getWithoutIVA()
    {
        return $this->withoutIVA;
    }

    /**
     * Set CID
     *
     * @param string $CID
     *
     * @return Contract
     */
    public function setCID($string)
    {
        $this->CID = $string;

        return $this;
    }

    /**
     * Get CID
     *
     * @return string
     */
    public function getCID()
    {
        return $this->CID;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
