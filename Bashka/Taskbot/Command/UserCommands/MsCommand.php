<?php

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * Class MsCommand
 */
class MsCommand extends MarksCommand
{
    protected $name = 'ms';
    protected $description = 'Псевдоним команды /marks';
    protected $usage = '/ms [<mark> ...|*]';
}
