<?php
namespace Model\Mark;

/**
 * @Entity(repositoryClass="Model\Mark\MarkRepository")
 * @Table(name="mark")
 */
class Mark{
  /**
   * @var int
   * @Id
   * @Column(type="integer")
   * @GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @Column(type="string", length=512, nullable=false)
   */
  private $name;

  public function __construct($name){
    $this->name = $name;
  }

  // Getters and Setters
  public function getId(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }
}
