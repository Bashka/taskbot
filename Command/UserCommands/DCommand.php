<?php
namespace Longman\TelegramBot\Commands\UserCommands;

class dCommand extends doneCommand{
  protected $name = 'd';
  protected $description = 'Псевдоним команды /done';
  protected $usage = '/d <task_id>';
}
