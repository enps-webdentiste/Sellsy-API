<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agenda
 *
 * @ORM\Table(name="agenda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendaRepository")
 */
class Agenda
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
     * @ORM\Column(name="agendaid", type="integer")
     */
    private $agendaid;
	
	/**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="agendas")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;
	
	/**
     * Many Agenda has One Agendatype.
     * @ORM\ManyToOne(targetEntity="Agendatype")
     * @ORM\JoinColumn(name="agendatype_id", referencedColumnName="id")
     */
    private $agendatype;

    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime")
     */
    private $end;

    /**
     * @var float
     *
     * @ORM\Column(name="timestampStart", type="float")
     */
    private $timestampStart;

    /**
     * @var float
     *
     * @ORM\Column(name="timestampEnd", type="float")
     */
    private $timestampEnd;

    
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;
	
	/**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=255 , nullable=true)
     */
    private $actif;


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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return Tagenda
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return Tagenda
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set timestampStart
     *
     * @param float $timestampStart
     *
     * @return Tagenda
     */
    public function setTimestampStart($timestampStart)
    {
        $this->timestampStart = $timestampStart;

        return $this;
    }

    /**
     * Get timestampStart
     *
     * @return float
     */
    public function getTimestampStart()
    {
        return $this->timestampStart;
    }

    /**
     * Set timestampEnd
     *
     * @param float $timestampEnd
     *
     * @return Tagenda
     */
    public function setTimestampEnd($timestampEnd)
    {
        $this->timestampEnd = $timestampEnd;

        return $this;
    }

    /**
     * Get timestampEnd
     *
     * @return float
     */
    public function getTimestampEnd()
    {
        return $this->timestampEnd;
    }

    

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Tagenda
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }
	
	/**
     * Set agendaid
     *
     * @param int $agendaid
     *
     * @return Agenda
     */
    public function setAgendaid($agendaid)
    {
        $this->agendaid = $agendaid;

        return $this;
    }

    /**
     * Get agendaid
     *
     * @return string
     */
    public function getAgendaid()
    {
        return $this->agendaid;
    }
	
	/**
     * Set client
     *
     * @param Client $client
     *
     * @return Agenda
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
	
	/**
     * Set agendatype
     *
     * @param Agendatype $agendatype
     *
     * @return Agenda
     */
    public function setAgendatype($agendatype)
    {
        $this->agendatype = $agendatype;

        return $this;
    }

    /**
     * Get agendatype
     *
     * @return Agendatype
     */
    public function getAgendatype()
    {
        return $this->agendatype;
    }
	
	/**
     * Set type
     *
     * @param string $type
     *
     * @return Agenda
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Agenda
     */
    public function getType()
    {
        return $this->type;
    }
	
	/**
     * Set actif
     *
     * @param string $actif
     *
     * @return Agenda
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

