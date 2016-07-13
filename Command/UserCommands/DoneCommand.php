<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Bashka\Taskbot\Command\AbstractUserCommand;
use Longman\TelegramBot\Request;

class doneCommand extends AbstractUserCommand{
  protected $name = 'done';
  protected $description = 'Пометить задачу как исполненную';
  protected $usage = '/done <task_id>';
  protected $version = '0.0.1';
  protected $enabled = true;
  protected $public = true;

  // Dependency
  protected $tasks;

  public function execute(){
    $message = $this->getMessage();
    $text = $message->getText(true);

    if(empty($text)){
      $response = chr(0xF0) . chr(0x9F) . chr(0x98) . chr(0x95);
    }
    else{
      $textLines = explode("\n", $text);
      
      if(preg_match('/^\d+$/', $textLines[0])){
        $response = $this->doneTaskAction(
          (int) $textLines[0]
        );
      }
      else{
        $response = chr(0xF0) . chr(0x9F) . chr(0x98) . chr(0x95);
      }
    }

    return Request::sendMessage([
      'chat_id' => $message->getChat()->getId(),
      'text' => $response,
    ]);
  }

  // Actions
  public function doneTaskAction($taskId){
    $task = $this->tasks->find($taskId);
    if(!$task){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    $task->complete();

    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8D);
  }
}
