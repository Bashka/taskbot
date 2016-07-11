<?php
namespace Longman\TelegramBot\Commands\UserCommands;
require_once(__DIR__ . '/DoneCommand.php');

class dCommand extends doneCommand{
  protected $name = 'd';
  protected $description = 'Псевдоним команды /done';
  protected $usage = '/d <task_id>';
}
