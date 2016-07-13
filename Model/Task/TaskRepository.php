<?php
namespace Bashka\Taskbot\Model\Task;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class TaskRepository extends EntityRepository{
  public function fetchByMarks(Collection $marks){
    $targetMarksIds = [];
    $hasCompleteMark = false; // Флаг присутствия метки "выполнено"
    $hasToday = false; // Флаг присутствия метки "сегодня"
    foreach($marks as $mark){
      if($mark->getName() == 'выполнено'){
        $hasCompleteMark = true;
      }
      elseif($mark->getName() == 'сегодня'){
        $hasToday = true;
      }
      elseif(!is_null($mark->getId())){
        $targetMarksIds[] = $mark->getId();
      }
    }

    $rsm = new ResultSetMappingBuilder($this->_em);
    $rsm->addRootEntityFromClassMetadata(Task::class, 't');

    $sql = 'SELECT t.* FROM `task` AS t ';
    $sql .= 'INNER JOIN `task_mark` ON t.id = task_mark.task ';

    $where = [];
    if(count($targetMarksIds)){
      $where[] = 'task_mark.mark IN (' . implode(',', $targetMarksIds) . ')';
    }
    if($hasCompleteMark){
      $where[] = 't.completed_at IS NOT NULL';
      if($hasToday){
        $where[] = 't.completed_at > DATE_ADD(NOW(), INTERVAL -1 DAY)';
      }
    }
    else{
      $where[] = 't.completed_at IS NULL';
      if($hasToday){
        $where[] = 't.created_at > DATE_ADD(NOW(), INTERVAL -1 DAY)';
      }
    }
    if(count($where)){
      $sql .= 'WHERE ' . implode(' AND ', $where) . ' ';
    }

    $sql .= 'GROUP BY t.id ';
    if(count($targetMarksIds)){
      $sql .= 'HAVING COUNT(*) = ' . count($targetMarksIds);
    }

    return $this->_em->createNativeQuery($sql, $rsm)->getResult();
  }

  public function add(Task $task){
    $this->_em->persist($task);
  }
}
