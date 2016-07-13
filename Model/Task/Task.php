<?php
namespace Bashka\Taskbot\Model\Task;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Bashka\Taskbot\Model\User\User;
use Bashka\Taskbot\Model\Mark\Mark;

/**
 * @Entity(repositoryClass="Bashka\Taskbot\Model\Task\TaskRepository")
 * @Table(name="task")
 */
class Task{
  /**
   * @var int
   * @Id
   * @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(type="string", length=1024, nullable=false)
   */
  private $title;

  /**
   * @Column(type="string")
   */
  private $description;

  /**
   * @Column(name="created_at", type="datetime")
   */
  private $created;

  /**
   * @Column(name="updated_at", type="datetime")
   */
  private $updated;

  /**
   * @Column(name="completed_at", type="datetime")
   */
  private $completed;

  /**
   * @ManyToMany(targetEntity="Bashka\Taskbot\Model\Mark\Mark")
   * @JoinTable(name="task_mark",
   * joinColumns={@JoinColumn(name="task", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="mark",referencedColumnName="id")}
   * )
   */
  private $marks;

  /**
   * @ManyToMany(targetEntity="Bashka\Taskbot\Model\User\User")
   * @JoinTable(name="subscriber",
   * joinColumns={@JoinColumn(name="task", referencedColumnName="id")},
   * inverseJoinColumns={@JoinColumn(name="user",referencedColumnName="id")}
   * )
   */
  private $subscribers;

  public function __construct($title){
    $this->title = $title;
    $this->description = '';
    $this->created = new \DateTime;
    $this->updated = new \DateTime;
    $this->marks = new ArrayCollection;
    $this->subscribers = new ArrayCollection;
  }

  // Getters and Setters
  public function getId(){
    return $this->id;
  }

  public function setTitle($title){
    $this->title = $title;
    $this->updated = new \DateTime;
  }
  
  public function getTitle(){
    return $this->title;
  }

  public function setDescription($description){
    $this->description = $description;
    $this->updated = new \DateTime;
  }
  
  public function getDescription(){
    return $this->description;
  }

  public function getCreated(){
    return $this->created;
  }

  public function getUpdated(){
    return $this->updated;
  }

  public function isComplete(){
    return !is_null($this->completed);
  }

  public function getCompleted(){
    return $this->completed;
  }

  public function getMarks(){
    return $this->marks;
  }

  public function setMarks(Collection $marks){
    $this->updated = new \DateTime;
    $this->marks->clear();
    foreach($marks as $mark){
      $this->marks->add($mark);
    }
  }

  public function mark(Mark $mark){
    if($this->marks->contains($mark)){
      return;
    }
    $this->marks->add($mark);
  }

  public function unmark(Mark $mark){
    $this->marks->removeElement($mark);
  }

  public function setSubscribers(Collection $subscribers){
    $this->subscribers->clear();
    foreach($subscribers as $subscriber){
      $this->subscribers->add($subscriber);
    }
  }

  public function subscribe(User $subscriber){
    if($this->subscribers->contains($subscriber)){
      return;
    }
    $this->subscribers->add($subscriber);
  }

  public function unsubscribe(User $subscriber){
    $this->subscribers->removeElement($subscriber);
  }
  
  public function getSubscribers(){
    return $this->subscribers;
  }

  // Actions
  public function complete(){
    if($this->isComplete()){
      return;
    }

    $this->completed = new \DateTime;
  }
}
