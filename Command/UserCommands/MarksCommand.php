<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Bashka\Taskbot\Command\AbstractUserCommand;
use Longman\TelegramBot\Request;

class marksCommand extends AbstractUserCommand{
  protected $name = 'marks';
  protected $description = 'Установить текущие метки или посмотреть их';
  protected $usage = '/marks [<mark> ...|*]';
  protected $version = '0.0.1';
  protected $enabled = true;
  protected $public = true;

  // Dependency
  protected $currentUser;
  protected $marks;

  public function execute(){
    $message = $this->getMessage();
    $header = $message->getText(true);

    if(empty(trim($header))){
      $response = $this->currentMarksListAction();
    }
    elseif($header == '*'){
      $response = $this->marksListAction();
    }
    else{
      $response = $this->setCurrentMarksAction(explode(' ', $header));
    }

    return Request::sendMessage([
      'chat_id' => $message->getChat()->getId(),
      'text' => $response,
    ]);
  }

  // Actions
  public function currentMarksListAction(){
    $marksNames = [];
    foreach($this->currentUser->getCurrentMarks() as $mark){
      $marksNames[] = $mark->getName();
    }

    if(!count($marksNames)){
      return 'нет';
    }

    return implode(', ', $marksNames);
  }

  public function marksListAction(){
    $marksNames = [];
    foreach($this->marks->findBy([]) as $mark){
      $marksNames[] = $mark->getName();
    }

    if(!count($marksNames)){
      return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
    }

    sort($marksNames);

    return implode("\n", $marksNames);
  }

  public function setCurrentMarksAction(array $marksNames){
    $this->currentUser->setCurrentMarks($this->marks->fetchByName($marksNames));

    return chr(0xF0) . chr(0x9F) . chr(0x91) . chr(0x8C);
  }
}
