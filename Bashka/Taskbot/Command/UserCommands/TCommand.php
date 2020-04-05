<?php

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * Class TCommand
 */
class TCommand extends TaskCommand
{
    protected $name = 't';
    protected $description = 'Псевдоним команды /task';
    protected $usage = '/t <task_id> [t|d|c|m|s|us]|<task_title>';
}
