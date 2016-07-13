<?php
namespace Bashka\Taskbot\Model\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="Bashka\Taskbot\Model\User\UserRepository")
 * @Table(name="user")
 */
class User{
  /**
   * @var int
   * @Id
   * @Column(type="integer")
   */
  private $id;

  /**
   * @Column(name="first_name", type="string", length=255, nullable=false)
   */
  private $firstName;

  /**
   * @Column(name="last_name", type="string", length=255)
   */
  private $lastName;

  /**
   * @Column(name="username", type="string", length=255)
   */
  private $userName;

  /**
   * @Column(name="created_at", type="datetime")
   */
  private $created;

  /**
   * @Column(name="updated_at", type="datetime")
   */
  private $updated;

  /**
   * @ManyToMany(targetEntity="Bashka\Taskbot\Model\Mark\Mark")
   * @JoinTable(name="user_mark",
   * joinColumns={@JoinColumn(name="user", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="mark",referencedColumnName="id")}
   * )
   */
  private $currentMarks;

  public function __construct(){
    $this->currentMarks = new ArrayCollection;
  }

  // Getters and Setters
  public function getId(){
    return $this->id;
  }

  public function getFirstName(){
    return $this->firstName;
  }

  public function getLastName(){
    return $this->lastName;
  }

  public function getUserName(){
    return $this->userName;
  }

  public function getCurrentMarks(){
    return $this->currentMarks;
  }

  public function setCurrentMarks(Collection $marks){
    $this->currentMarks->clear();
    foreach($marks as $mark){
      $this->currentMarks->add($mark);
    }
  }
}
