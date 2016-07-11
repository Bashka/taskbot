<?php
namespace Command;
use Longman\TelegramBot\Commands\UserCommand;
use Bricks\ServiceLocator\Manager as Locator;
use Model\User\UserRepository;

abstract class AbstractUserCommand extends UserCommand{
  protected function injectDependency(Locator $locator){
    if(property_exists($this, 'currentUser')){
      $users = $locator['entity_manager']->getRepository(\Model\User\User::class);
      $this->currentUser = $users->find($this->getMessage()->getFrom()->getId());
    }
    if(property_exists($this, 'users')){
      $this->users = $locator['entity_manager']->getRepository(\Model\User\User::class);
    }
    if(property_exists($this, 'marks')){
      $this->marks = $locator['entity_manager']->getRepository(\Model\Mark\Mark::class);
    }
    if(property_exists($this, 'tasks')){
      $this->tasks = $locator['entity_manager']->getRepository(\Model\Task\Task::class);
    }
  }

  public function preExecute(){
    $this->injectDependency($this->getTelegram()->locator);
    return parent::preExecute();
  }
}
