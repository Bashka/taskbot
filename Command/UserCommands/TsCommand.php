<?php
namespace Longman\TelegramBot\Commands\UserCommands;

class tsCommand extends tasksCommand{
  protected $name = 'ts';
  protected $description = 'Псевдоним команды /tasks';
  protected $usage = '/ts [<mark> ...]';
}
