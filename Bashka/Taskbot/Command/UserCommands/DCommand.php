<?php

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * Class DCommand
 */
class DCommand extends DoneCommand
{
    protected $name = 'd';
    protected $description = 'Псевдоним команды /done';
    protected $usage = '/d <task_id>';
}
