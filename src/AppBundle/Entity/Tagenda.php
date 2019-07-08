<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tagenda
 *
 * @ORM\Table(name="tagenda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagendaRepository")
 */
class Tagenda
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
     * @ORM\Column(name="idType", type="string", length=255)
     */
    private $idType;

    /**
     * @var string
     *
     * @ORM\Column(name="relatedtype", type="string", length=255, nullable=true)
     */
    private $relatedtype;

    /**
     * @var int
     *
     * @ORM\Column(name="relatedid", type="integer")
     */
    private $relatedid;

    /**
     * @var int
     *
     * @ORM\Column(name="ownerid", type="integer")
     */
    private $ownerid;

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
     * @var int
     *
     * @ORM\Column(name="labelid", type="integer")
     */
    private $labelid;

    /**
     * @var string
     *
     * @ORM\Column(name="labelname", type="string", length=255)
     */
    private $labelname;

    /**
     * @var string
     *
     * @ORM\Column(name="ownerfullname", type="string", length=255)
     */
    private $ownerfullname;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;


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
     * Set idType
     *
     * @param string $idType
     *
     * @return Tagenda
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return string
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set relatedtype
     *
     * @param string $relatedtype
     *
     * @return Tagenda
     */
    public function setRelatedtype($relatedtype)
    {
        $this->relatedtype = $relatedtype;

        return $this;
    }

    /**
     * Get relatedtype
     *
     * @return string
     */
    public function getRelatedtype()
    {
        return $this->relatedtype;
    }

    /**
     * Set relatedid
     *
     * @param integer $relatedid
     *
     * @return Tagenda
     */
    public function setRelatedid($relatedid)
    {
        $this->relatedid = $relatedid;

        return $this;
    }

    /**
     * Get relatedid
     *
     * @return int
     */
    public function getRelatedid()
    {
        return $this->relatedid;
    }

    /**
     * Set ownerid
     *
     * @param integer $ownerid
     *
     * @return Tagenda
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
     * Set labelid
     *
     * @param integer $labelid
     *
     * @return Tagenda
     */
    public function setLabelid($labelid)
    {
        $this->labelid = $labelid;

        return $this;
    }

    /**
     * Get labelid
     *
     * @return int
     */
    public function getLabelid()
    {
        return $this->labelid;
    }

    /**
     * Set labelname
     *
     * @param string $labelname
     *
     * @return Tagenda
     */
    public function setLabelname($labelname)
    {
        $this->labelname = $labelname;

        return $this;
    }

    /**
     * Get labelname
     *
     * @return string
     */
    public function getLabelname()
    {
        return $this->labelname;
    }

    /**
     * Set ownerfullname
     *
     * @param string $ownerfullname
     *
     * @return Tagenda
     */
    public function setOwnerfullname($ownerfullname)
    {
        $this->ownerfullname = $ownerfullname;

        return $this;
    }

    /**
     * Get ownerfullname
     *
     * @return string
     */
    public function getOwnerfullname()
    {
        return $this->ownerfullname;
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
}

