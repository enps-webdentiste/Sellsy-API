<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tclient
 *
 * @ORM\Table(name="tclient")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TclientRepository")
 */
class Tclient
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
     * @var string
     *
     * @ORM\Column(name="thirdurl", type="string", length=255, nullable=true)
     */
    private $thirdurl;

    /**
     * @var string
     *
     * @ORM\Column(name="ident", type="string", length=255, nullable=true)
     */
    private $ident;

    /**
     * @var int
     *
     * @ORM\Column(name="ownerid", type="integer")
     */
    private $ownerid;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="maincontactid", type="integer", nullable=true)
     */
    private $maincontactid;

    /**
     * @var string
     *
     * @ORM\Column(name="relationType", type="string", length=255, nullable=true)
     */
    private $relationType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="siren", type="string", length=255, nullable=true)
     */
    private $siren;

    /**
     * @var string
     *
     * @ORM\Column(name="mainAddress", type="string", length=255, nullable=true)
     */
    private $mainAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="owner", type="string", length=255, nullable=true)
     */
    private $owner;


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
     * @return Tclient
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
     * Set ident
     *
     * @param string $ident
     *
     * @return Tclient
     */
    public function setIdent($ident)
    {
        $this->ident = $ident;

        return $this;
    }

    /**
     * Get ident
     *
     * @return string
     */
    public function getIdent()
    {
        return $this->ident;
    }
	
	/**
     * Set thirdurl
     *
     * @param string $thirdurl
     *
     * @return Tclient
     */
    public function setThirdurl($thirdurl)
    {
        $this->thirdurl = $thirdurl;

        return $this;
    }

    /**
     * Get thirdurl
     *
     * @return string
     */
    public function getThirdurl()
    {
        return $this->thirdurl;
    }

    /**
     * Set ownerid
     *
     * @param integer $ownerid
     *
     * @return Tclient
     */
    public function setOwnerid($ownerid)
    {
        $this->ownerid = $ownerid;

        return $this;
    }

    /**
     * Get ownerid
     *
     * @return int
     */
    public function getOwnerid()
    {
        return $this->ownerid;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Tclient
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set maincontactid
     *
     * @param integer $maincontactid
     *
     * @return Tclient
     */
    public function setMaincontactid($maincontactid)
    {
        $this->maincontactid = $maincontactid;

        return $this;
    }

    /**
     * Get maincontactid
     *
     * @return int
     */
    public function getMaincontactid()
    {
        return $this->maincontactid;
    }

    /**
     * Set relationType
     *
     * @param string $relationType
     *
     * @return Tclient
     */
    public function setRelationType($relationType)
    {
        $this->relationType = $relationType;

        return $this;
    }

    /**
     * Get relationType
     *
     * @return string
     */
    public function getRelationType()
    {
        return $this->relationType;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tclient
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
     * Set tel
     *
     * @param string $tel
     *
     * @return Tclient
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Tclient
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Tclient
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set siren
     *
     * @param string $siren
     *
     * @return Tclient
     */
    public function setSiren($siren)
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * Get siren
     *
     * @return string
     */
    public function getSiren()
    {
        return $this->siren;
    }

    /**
     * Set mainAddress
     *
     * @param string $mainAddress
     *
     * @return Tclient
     */
    public function setMainAddress($mainAddress)
    {
        $this->mainAddress = $mainAddress;

        return $this;
    }

    /**
     * Get mainAddress
     *
     * @return string
     */
    public function getMainAddress()
    {
        return $this->mainAddress;
    }

    /**
     * Set owner
     *
     * @param string $owner
     *
     * @return Tclient
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }
}

