<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Bashka\Taskbot\Command\AbstractUserCommand;
use Longman\TelegramBot\Request;
use Bashka\Taskbot\Model\Task\Task;
use Bashka\Taskbot\Model\User\User;

class taskCommand extends AbstractUserCommand{
  protected $name = 'task';
  protected $description = 'Создать, отредактировать или посмотреть задачу';
  protected $usage = '/task <task_id> [t|d|c|m|s|us]|<task_title>';
  protected $version = '0.0.1';
  protected $enabled = true;
  protected $public = true;

  // Dependency
  protected $currentUser;
  protected $users;
  protected $marks;
  protected $tasks;

  public function execute(){
    $message = $this->getMessage();
    $text = $message->getText(true);

    if(empty($text)){
      $response = chr(0xF0) . chr(0x9F) . chr(0x98) . chr(0x95);
    }
    else{
      $textLines = explode("\n", $text);
      $match = [];

      if(preg_match('/^[^\d].*$/', $textLines[0])){
        $title = array_shift($textLines);
        $description = implode("\n", $textLines);
        $response = $this->createTaskAction($title, $description);
      }
      elseif(preg_match('/^\d+$/', $textLines[0])){
        $response = $this->viewTaskAction((int) $textLines[0]);
      }
      elseif(preg_match('/^(\d+) (t|d|c|m|s|us|title|description|comment|marks|subscribe|unsubscribe)$/', $textLines[0], $match)){
        array_shift($textLines);
        if(in_array($match[2], ['t', 'title'])){
          $response = $this->updateTaskAction((int) $match[1], $textLines[0]);
        }
        elseif(in_array($match[2], ['d', 'description'])){
          $response = $this->updateTaskAction((int) $match[1], null, implode("\n", $textLines));
        }
        elseif(in_array($match[2], ['c', 'comment'])){
          $response = $this->updateTaskAction((int) $match[1], null, null,
            '_' . $this->currentUser->getFirstName() . ' ' . $this->currentUser->getLastName() . ' ' .
            '[' . (new \DateTime)->format('d.m.Y H:i') . ']_' . "\n" .
            implode("\n", $textLines)
          );
        }
        elseif(in_array($match[2], ['m', 'marks'])){
          $response = $this->updateTaskAction((int) $match[1], null, null, null, explode(' ', $textLines[0]));
        }
        elseif(in_array($match[2], ['s', 'subscribe'])){
          $subscribers = isset($textLines[0])? explode(' ', $textLines[0]) : null;
          $response = $this->subscribeToTaskAction((int) $match[1], $subscribers);
        }
        elseif(in_array($match[2], ['us', 'unsubscribe'])){
          $subscribers = isset($textLines[0])? explode(' ', $textLines[0]) : null;
          $response = $this->unsubscribeFromTaskAction((int) $match[1], $subscribers);
        }
        else{
          $response = chr(0xF0) . chr(0x9F) . chr(0x98) . chr(0x95);
        }
      }
      else{
        $response = chr(0xF0) . chr(0x9F) . chr(0x98) . chr(0x95);
      }
    }

    return Request::sendMessage([
      'chat_id' => $message->getChat()->getId(),
      'parse_mode' => 'MARKDOWN',
      'text' => $response,
    ]);
  }

  // Actions
  public function createTaskAction($title, $description){
    $currentMarks = $this->currentUser->getCurrentMarks();
    if(!count($currentMarks)){
      $currentMarks = $this->marks->fetchByName(['нет']);
    }

    $task = new Task($title);
    $task->setDescription($description);
    $task->setMarks($currentMarks);
    $task->subscribe($this->currentUser);

    foreach($currentMarks as $mark){
      if($mark->getName()[0] != '@'){
        continue;
      }

      $targetSubscriber = $this->users->findByUsername(substr($mark->getName(), 1));
      if(is_null($targetSubscriber)){
        continue;
      }

      $task->subscribe($targetSubscriber);
    }

    $this->tasks->add($task);

    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8C);
  }

  public function updateTaskAction($taskId, $title = null, $description = null, $comment = null, array $marksNames = null){
    $task = $this->tasks->find($taskId);
    if(!$task){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    if(!is_null($title)){
      $notify = chr(0xE2) . chr(0x9C) . chr(0x8F) . ' ' . $task->getId() . "\n";
      $notify .= $task->getTitle() . "\n";
      $notify .= chr(0xE2) . chr(0xAC) . chr(0x87) . "\n";
      $notify .= $title;
    }
    if(!is_null($description)){
      $notify = chr(0xE2) . chr(0x9C) . chr(0x8F) . ' ' . $task->getId() . "\n";
      $notify .= $task->getDescription() . "\n";
      $notify .= chr(0xE2) . chr(0xAC) . chr(0x87) . "\n";
      $notify .= $description;
    }
    if(!is_null($comment)){
      $notify = chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x84) . ' ' . $task->getId() . "\n";
      $notify .= $comment;
    }

    if(!is_null($title)){
      $task->setTitle($title);
    }
    if(!is_null($description)){
      $task->setDescription($description);
    }
    if(!is_null($comment)){
      $task->setDescription($task->getDescription() . "\n\n" . $comment);
    }
    if(!is_null($marksNames)){
      $task->setMarks($this->marks->fetchByName($marksNames));
    }

    // Уведомление подписчиков
    if(!is_null($title) || !is_null($description) || !is_null($comment)){
      foreach($task->getSubscribers() as $subscribe){
        if($subscribe->getId() == $this->currentUser->getId()){
          continue;
        }

        Request::sendMessage([
          'chat_id' => $subscribe->getId(),
          'parse_mode' => 'MARKDOWN',
          'text' => $notify,
        ]);
      }
    }

    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8C);
  }

  public function viewTaskAction($taskId){
    $task = $this->tasks->find($taskId);
    if(!$task){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    $marksNames = [];
    foreach($task->getMarks() as $mark){
      $marksNames[] = $mark->getName();
    }

    $response = '';
    $response .= chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAB) . ' ' . $task->getCreated()->format('d.m.Y H:i') . "\n";
    if($task->isComplete()){
      $response .= chr(0xF0) . chr(0x9F) . chr(0x8F) . chr(0x81) . ' ' . $task->getCompleted()->format('d.m.Y H:i') . "\n";
    }
    $response .= "\n";
    $response .= chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0x8C) . ' ' . implode(', ', $marksNames) . "\n";
    $response .= '*' . $task->getTitle() . '*' . "\n";
    $response .= $task->getDescription() . "\n";

    return $response;
  }

  public function subscribeToTaskAction($taskId, array $subscribers = null){
    $task = $this->tasks->find($taskId);
    if(!$task){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    if(is_null($subscribers)){
      $subscribers = [$this->currentUser];
    }
    
    foreach($subscribers as $targetSubscriber){
      if(!$targetSubscriber instanceof User){
        if($targetSubscriber[0] != '@'){
          continue;
        }
        $targetSubscriber = $this->users->findByUsername(substr($targetSubscriber, 1));
        if(is_null($targetSubscriber)){
          continue;
        }
      }

      $task->subscribe($targetSubscriber);
    }

    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8C);
  }

  public function unsubscribeFromTaskAction($taskId, array $subscribers = null){
    $task = $this->tasks->find($taskId);
    if(!$task){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    if(is_null($subscribers)){
      $subscribers = [$this->currentUser];
    }

    foreach($subscribers as $targetSubscriber){
      if(!$targetSubscriber instanceof User){
        if($targetSubscriber[0] != '@'){
          continue;
        }
        $targetSubscriber = $this->users->findByUsername(substr($targetSubscriber, 1));
        if(is_null($targetSubscriber)){
          continue;
        }
      }

      $task->unsubscribe($targetSubscriber);
    }
    
    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8C);
  }
}
