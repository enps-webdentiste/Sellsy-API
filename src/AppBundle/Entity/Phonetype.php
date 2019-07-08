<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phonetype
 *
 * @ORM\Table(name="phonetype")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhonetypeRepository")
 */
class Phonetype
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
     * @ORM\Column(name="calltypeid", type="integer", unique=true)
     */
    private $calltypeid;

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
     * Set calltypeid
     *
     * @param integer $calltypeid
     *
     * @return Tcalltype
     */
    public function setCalltypeid($calltypeid)
    {
        $this->calltypeid = $calltypeid;

        return $this;
    }

    /**
     * Get calltypeid
     *
     * @return int
     */
    public function getCalltypeid()
    {
        return $this->calltypeid;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Tcalltype
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

