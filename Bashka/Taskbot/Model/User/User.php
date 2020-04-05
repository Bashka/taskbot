<?php

namespace Bashka\Taskbot\Model\User;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bashka\Taskbot\Model\User\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $userName;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated;

    /**
     * @ORM\ManyToMany(targetEntity="Bashka\Taskbot\Model\Mark\Mark")
     * @ORM\JoinTable(name="user_mark",
     * joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="mark",referencedColumnName="id")}
     * )
     */
    private $currentMarks;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->currentMarks = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return ArrayCollection
     */
    public function getCurrentMarks()
    {
        return $this->currentMarks;
    }

    /**
     * @param Collection $marks
     */
    public function setCurrentMarks(Collection $marks)
    {
        $this->currentMarks->clear();
        foreach ($marks as $mark) {
            $this->currentMarks->add($mark);
        }
    }
}
