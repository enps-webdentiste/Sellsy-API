<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tphone
 *
 * @ORM\Table(name="tphone")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TphoneRepository")
 */
class Tphone
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
     * @var int
     *
     * @ORM\Column(name="thirdid", type="integer")
     */
    private $thirdid;

    /**
     * @var int
     *
     * @ORM\Column(name="peopleid", type="integer", nullable=true)
     */
    private $peopleid;

    /**
     * @var float
     *
     * @ORM\Column(name="start", type="float", nullable=true)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="thirdFullName", type="string", length=255, nullable=true)
     */
    private $thirdFullName;

    /**
     * @var string
     *
     * @ORM\Column(name="thirdUrl", type="string", length=255, nullable=true)
     */
    private $thirdUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="staffFullName", type="string", length=255, nullable=true)
     */
    private $staffFullName;

    /**
     * @var string
     *
     * @ORM\Column(name="staffEmail", type="string", length=255, nullable=true)
     */
    private $staffEmail;


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
     * Set thirdid
     *
     * @param integer $thirdid
     *
     * @return Tphone
     */
    public function setThirdid($thirdid)
    {
        $this->thirdid = $thirdid;

        return $this;
    }

    /**
     * Get thirdid
     *
     * @return int
     */
    public function getThirdid()
    {
        return $this->thirdid;
    }

    /**
     * Set peopleid
     *
     * @param integer $peopleid
     *
     * @return Tphone
     */
    public function setPeopleid($peopleid)
    {
        $this->peopleid = $peopleid;

        return $this;
    }

    /**
     * Get peopleid
     *
     * @return int
     */
    public function getPeopleid()
    {
        return $this->peopleid;
    }

    /**
     * Set start
     *
     * @param float $start
     *
     * @return Tphone
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return float
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Tphone
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set thirdFullName
     *
     * @param string $thirdFullName
     *
     * @return Tphone
     */
    public function setThirdFullName($thirdFullName)
    {
        $this->thirdFullName = $thirdFullName;

        return $this;
    }

    /**
     * Get thirdFullName
     *
     * @return string
     */
    public function getThirdFullName()
    {
        return $this->thirdFullName;
    }

    /**
     * Set thirdUrl
     *
     * @param string $thirdUrl
     *
     * @return Tphone
     */
    public function setThirdUrl($thirdUrl)
    {
        $this->thirdUrl = $thirdUrl;

        return $this;
    }

    /**
     * Get thirdUrl
     *
     * @return string
     */
    public function getThirdUrl()
    {
        return $this->thirdUrl;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Tphone
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set staffFullName
     *
     * @param string $staffFullName
     *
     * @return Tphone
     */
    public function setStaffFullName($staffFullName)
    {
        $this->staffFullName = $staffFullName;

        return $this;
    }

    /**
     * Get staffFullName
     *
     * @return string
     */
    public function getStaffFullName()
    {
        return $this->staffFullName;
    }

    /**
     * Set staffEmail
     *
     * @param string $staffEmail
     *
     * @return Tphone
     */
    public function setStaffEmail($staffEmail)
    {
        $this->staffEmail = $staffEmail;

        return $this;
    }

    /**
     * Get staffEmail
     *
     * @return string
     */
    public function getStaffEmail()
    {
        return $this->staffEmail;
    }
}

