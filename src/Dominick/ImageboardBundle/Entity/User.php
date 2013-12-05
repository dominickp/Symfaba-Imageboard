<?php

namespace Dominick\ImageboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Dominick\ImageboardBundle\Repository\Users")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="60")
     * @ORM\Column(type="string", length=60, unique=true)
     * @var string
     */
    protected $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="60")
     * @ORM\Column(type="string", length=60)
     * @var string
     */
    protected $displayname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="6", minMessage="Your password is not long enough!")
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    protected $password;

    /**
     * @ORM\Column(type="string", length=64)
     * @var string
     */
    protected $salt;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @var boolean
     */
    protected $isActive;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $updated;

	/**
	 * @ORM\OneToMany(targetEntity="Thread", mappedBy="user")
	 * @var ArrayCollection
	 */
	protected $threads;

    public function __construct()
    {
        $this->isActive = true;
        $this->salt = md5(uniqid(null, true));
    //    $this->roles = new ArrayCollection();

        $this->roles = new ArrayCollection();
        $this->expenses = new ArrayCollection();

        $this->created = new \DateTime("now");
    }

    public function __toString()
    {
        return $this->displayname;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param string $displayname
     * @return User
     */
    public function setDisplayname($displayname)
    {
        $this->displayname = $displayname;

        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayname()
    {
        return $this->displayname;
    }

    /**
     * @param integer $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param Role
     * @return User
     */
    public function addRole(Role $role)
    {
        $this->roles->add($role);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return User
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime("now");

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt,
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->isActive;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Dominick\ImageboardBundle\Entity\Role $roles
     */
    public function removeRole(\Dominick\ImageboardBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }
}