<?php

namespace Dominick\ImageboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="replies")
 * @ORM\HasLifecycleCallbacks
 */
class Reply
{
	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var integer
	 */
	protected $id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="replies")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 * @var User
	 */
	protected $user;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(max="255")
	 * @ORM\Column(type="string", length=255)
	 * @var string
	 */
	protected $image;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(max="1024")
	 * @ORM\Column(type="string", length=1024)
	 * @var string
	 */
	protected $message;

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
}