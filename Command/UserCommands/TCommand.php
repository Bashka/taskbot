<?php
namespace Longman\TelegramBot\Commands\UserCommands;
require_once(__DIR__ . '/TaskCommand.php');

class tCommand extends taskCommand{
  protected $name = 't';
  protected $description = 'Псевдоним команды /task';
  protected $usage = '/t <task_id> [t|d|c|m|s|us]|<task_title>';
}
