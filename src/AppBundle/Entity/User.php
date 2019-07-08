<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
	
	/**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @ORM\JoinTable(name="utilisateur_groupe",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
	
	/**
	 * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
	 * @Assert\Length(
	 *     min=2,
	 *     minMessage="Le prénom devra comporter 2 caractères minimum",
	 * )
	 */

    private $firstname;

    /**
     * @ORM\Column(name="lastname", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     minMessage="Le nom devra comporter 2 caractères minimum",
     * )
     */
    private $lastname;
	
	/**
     * @var int
     *
     * @ORM\Column(name="sincemonth", type="integer", nullable=true)
     */
    private $sincemonth;
	
	/**
	* @ORM\ManyToMany(targetEntity="Owner", inversedBy="users")
	* @ORM\JoinTable(name="user_owner_pref")
	*/
	private $owners;
	
	/**
     * @var int
     *
     * @ORM\Column(name="showdelete", type="boolean", nullable=true)
     */
    private $showdelete;
	

    public function __construct()
    {
        parent::__construct();
        // your own logic
		$this->owners = new ArrayCollection();
    }
	
	 /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }
	
	/**
     * Set sincemonth
     *
     * @param integer $sincemonth
     *
     * @return User
     */
    public function setSincemonth($sincemonth)
    {
        $this->sincemonth = $sincemonth;

        return $this;
    }

    /**
     * Get sincemonth
     *
     * @return int
     */
    public function getSincemonth()
    {
        return $this->sincemonth;
    }
	
	public function addOwner(Owner $owner)
	{
		if (!$this->owners->contains($owner)) {
			$this->owners[] = $owner;
		}
		return $this;
	}

	public function setOwners(ArrayCollection $owners)
	{
		$this->owners = $owners;

		return $this;
	}

	public function removeOwner(Owner $owner)
	{
		$this->owners->removeElement($owner);
	}

	public function getOwners()
	{
		return $this->owners;
	}
	
	/**
     * Set showdelete
     *
     * @param integer $showdelete
     *
     * @return User
     */
    public function setShowdelete($showdelete)
    {
        $this->showdelete = $showdelete;

        return $this;
    }

    /**
     * Get showdelete
     *
     * @return int
     */
    public function getShowdelete()
    {
        return $this->showdelete;
    }
}