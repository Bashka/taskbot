<?php
namespace Longman\TelegramBot\Commands\UserCommands;
require_once(__DIR__ . '/TasksCommand.php');

class tsCommand extends tasksCommand{
  protected $name = 'ts';
  protected $description = 'Псевдоним команды /tasks';
  protected $usage = '/ts [<mark> ...]';
}
