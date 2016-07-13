<?php
namespace Longman\TelegramBot\Commands\UserCommands;

class msCommand extends marksCommand{
  protected $name = 'ms';
  protected $description = 'Псевдоним команды /marks';
  protected $usage = '/ms [<mark> ...|*]';
}
