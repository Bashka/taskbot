<?php
namespace Longman\TelegramBot\Commands\UserCommands;
require_once(__DIR__ . '/MarksCommand.php');

class msCommand extends marksCommand{
  protected $name = 'ms';
  protected $description = 'Псевдоним команды /marks';
  protected $usage = '/ms [<mark> ...|*]';
}
