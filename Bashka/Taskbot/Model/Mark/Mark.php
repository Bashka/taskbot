<?php

namespace Bashka\Taskbot\Model\Mark;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Bashka\Taskbot\Model\Mark\MarkRepository")
 * @ORM\Table(name="mark")
 */
class Mark
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512, nullable=false)
     */
    private $name;

    /**
     * Mark constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
    public function getName()
    {
        return $this->name;
    }
}
