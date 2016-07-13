<?php
namespace Bashka\Taskbot\Model\Mark;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class MarkRepository extends EntityRepository{
  /**
   * Предоставляет список меток по их именам.
   *
   * @param array $names Имена запрашиваемых меток.
   *
   * @return Collection Список меток.
   */
  public function fetchByName(array $names){
    $result = new ArrayCollection;

    // Получение созданных ранее меток
    foreach($this->findBy([]) as $mark){
      $p = array_search($mark->getName(), $names);
      if($p !== false){
        $result->add($mark);
        unset($names[$p]);
      }
    }

    // Добавление новых меток
    foreach($names as $name){
      $mark = new Mark($name);
      $this->_em->persist($mark);
      $result->add($mark);
    }

    return $result;
  }
}
