<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
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
     * @ORM\Column(name="clienturl", type="string", length=255, nullable=true)
     */
    private $clienturl;

    /**
     * @var string
     *
     * @ORM\Column(name="ident", type="string", length=255, nullable=true)
     */
    private $ident;

    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    
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
     * @ORM\Column(name="mobile", type="string", length=255, nullable=true)
     */
    private $mobile;

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
     * @ORM\OneToMany(targetEntity="Agenda", mappedBy="client")
     */
    private $agendas;
	
	/**
     * @ORM\OneToMany(targetEntity="Phone", mappedBy="client")
     */
    private $phones;
	
	/**
     * Many Client has One Owner.
     * @ORM\ManyToOne(targetEntity="Owner")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;
	
	/**
     * One Client has One Contact.
     * @ORM\ManyToOne(targetEntity="Contact")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;
	
	/**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=255, nullable=true)
     */
    private $actif;
	

    public function __construct()
    {
        $this->agendas = new ArrayCollection();
		$this->phones = new ArrayCollection();
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
     * Set clienturl
     *
     * @param string $clienturl
     *
     * @return Tclient
     */
    public function setClienturl($clienturl)
    {
        $this->clienturl = $clienturl;

        return $this;
    }

    /**
     * Get clienturl
     *
     * @return string
     */
    public function getClienturl()
    {
        return $this->clienturl;
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
     * @return Client
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
     * @return ArrayCollection
     */
    public function getAgendas()
    {
        return $this->agendas;
    }

    /**
     * Add Agenda
     *
     * @param Agenda $agenda
     * @return $this
     */
    public function addAgenda(Agenda $agenda)
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas[] = $agenda;
        }

        return $this;
    }
	
	/**
     * @return ArrayCollection
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add Phone
     *
     * @param Phone $phone
     * @return $this
     */
    public function addPhone(Phone $phone)
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
        }

        return $this;
    }
	
	/**
     * Set owner
     *
     * @return Client
     */
    public function setOwner(Owner $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return Entity
     */
    public function getOwner()
    {
        return $this->owner;
    }
	
	/**
     * Set contact
     *
     * @return Client
     */
    public function setContact(Contact $contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return Entity
     */
    public function getContact()
    {
        return $this->contact;
    }
	
	/**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Client
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }
	
	/**
     * Set actif
     *
     * @param string $actif
     *
     * @return Client
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return string
     */
    public function getActif()
    {
        return $this->actif;
    }

}

