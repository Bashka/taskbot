<?php

namespace Bashka\Taskbot\Model\Task;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Bashka\Taskbot\Model\User\User;
use Bashka\Taskbot\Model\Mark\Mark;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bashka\Taskbot\Model\Task\TaskRepository")
 * @ORM\Table(name="task")
 */
class Task
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1024, nullable=false)
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(name="completed_at", type="datetime")
     */
    private $completed;

    /**
     * @ORM\ManyToMany(targetEntity="Bashka\Taskbot\Model\Mark\Mark")
     * @ORM\JoinTable(name="task_mark",
     * joinColumns={@ORM\JoinColumn(name="task", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="mark",referencedColumnName="id")}
     * )
     */
    private $marks;

    /**
     * @ORM\ManyToMany(targetEntity="Bashka\Taskbot\Model\User\User")
     * @ORM\JoinTable(name="subscriber",
     * joinColumns={@ORM\JoinColumn(name="task", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="user",referencedColumnName="id")}
     * )
     */
    private $subscribers;

    /**
     * Task constructor.
     * @param $title
     * @throws \Exception
     */
    public function __construct($title)
    {
        $this->title = $title;
        $this->description = '';
        $this->created = new \DateTime;
        $this->updated = new \DateTime;
        $this->marks = new ArrayCollection;
        $this->subscribers = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $title
     * @throws \Exception
     */
    public function setTitle($title)
    {
        $this->title = $title;
        $this->updated = new \DateTime;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $description
     * @throws \Exception
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->updated = new \DateTime;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        return !is_null($this->completed);
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @return ArrayCollection
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * @param Collection $marks
     * @throws \Exception
     */
    public function setMarks(Collection $marks)
    {
        $this->updated = new \DateTime;
        $this->marks->clear();
        foreach ($marks as $mark) {
            $this->marks->add($mark);
        }
    }

    /**
     * @param Mark $mark
     */
    public function mark(Mark $mark)
    {
        if ($this->marks->contains($mark)) {
            return;
        }
        $this->marks->add($mark);
    }

    /**
     * @param Mark $mark
     */
    public function unmark(Mark $mark)
    {
        $this->marks->removeElement($mark);
    }

    /**
     * @param Collection $subscribers
     */
    public function setSubscribers(Collection $subscribers)
    {
        $this->subscribers->clear();
        foreach ($subscribers as $subscriber) {
            $this->subscribers->add($subscriber);
        }
    }

    /**
     * @param User $subscriber
     */
    public function subscribe(User $subscriber)
    {
        if ($this->subscribers->contains($subscriber)) {
            return;
        }
        $this->subscribers->add($subscriber);
    }

    /**
     * @param User $subscriber
     */
    public function unsubscribe(User $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }

    /**
     * @return ArrayCollection
     */
    public function getSubscribers()
    {
        return $this->subscribers;
    }

    /**
     * @throws \Exception
     */
    public function complete()
    {
        if ($this->isComplete()) {
            return;
        }

        $this->completed = new \DateTime;
    }
}
