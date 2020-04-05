<?php

namespace Longman\TelegramBot\Commands\UserCommands;

/**
 * Class TsCommand
 */
class TsCommand extends TasksCommand
{
    protected $name = 'ts';
    protected $description = 'Псевдоним команды /tasks';
    protected $usage = '/ts [<mark> ...]';
}
