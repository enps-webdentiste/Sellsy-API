<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Owner
 *
 * @ORM\Table(name="owner")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OwnerRepository")
 */
class Owner
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
     * @ORM\Column(name="ownerid", type="integer", unique=true)
     */
    private $ownerid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
	
	/**
     * @var string
     *
     * @ORM\Column(name="forename", type="string", length=255 , nullable=true)
     */
    private $forename;
	
	/**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=255 , nullable=true)
     */
    private $actif;
	
	/**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255 , nullable=true)
     */
    private $email;

	/**
	* @ORM\ManyToMany(targetEntity="User", mappedBy="owners")
	*/
	private $users;
	
	public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * Set ownerid
     *
     * @param integer $ownerid
     *
     * @return Owner
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
     * Set name
     *
     * @param string $name
     *
     * @return Owner
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
     * Set forename
     *
     * @param string $forename
     *
     * @return Owner
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }
	
	/**
     * Set actif
     *
     * @param string $actif
     *
     * @return Owner
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
	
	/**
     * Set email
     *
     * @param string $email
     *
     * @return Owner
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
	
	public function addUser(User $user)
	{
		if (!$this->users->contains($user)) {
			$this->users[] = $user;
		}
		return $this;
	}

	public function setUsers(ArrayCollection $users)
	{
		$this->users = $users;

		return $this;
	}

	public function removeUser(User $user)
	{
		$this->users->removeElement($user);
	}

	public function getUsers()
	{
		return $this->users;
	}
}

