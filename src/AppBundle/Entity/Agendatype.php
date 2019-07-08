<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agendatype
 *
 * @ORM\Table(name="agendatype")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgendatypeRepository")
 */
class Agendatype
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
     * @ORM\Column(name="labelid", type="integer", unique=true)
     */
    private $labelid;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;


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
     * Set labelid
     *
     * @param integer $labelid
     *
     * @return TagendaLabel
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
     * Set value
     *
     * @param string $value
     *
     * @return TagendaLabel
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}

