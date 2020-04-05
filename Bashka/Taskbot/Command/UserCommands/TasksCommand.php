<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Bashka\Taskbot\Command\AbstractUserCommand;
use Longman\TelegramBot\Request;
use Doctrine\Common\Collections\Collection;

/**
 * Class TasksCommand
 */
class TasksCommand extends AbstractUserCommand
{
    protected $name = 'tasks';
    protected $description = 'Список задач';
    protected $usage = '/tasks [<mark> ...]';
    protected $version = '0.0.1';
    protected $enabled = true;
    protected $public = true;

    // Dependency
    protected $currentUser;
    protected $marks;
    protected $tasks;

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse|mixed
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $text = $message->getText(true);

        if (empty(trim($text))) {
            $response = $this->tasksWithMarksListAction(
                $this->currentUser->getCurrentMarks()
            );
        } else {
            $response = $this->tasksWithMarksListAction(
                $this->marks->fetchByName(explode(' ', $text))
            );
        }

        return Request::sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'parse_mode' => 'MARKDOWN',
            'text' => $response,
        ]);
    }

    /**
     * @param Collection $targetMarks
     * @return string
     */
    public function tasksWithMarksListAction(Collection $targetMarks)
    {
        $result = [];
        foreach ($this->tasks->fetchByMarks($targetMarks) as $task) {
            $result[] = $task->getId() . ' - ' . $task->getTitle();
        }

        if (!count($result)) {
            return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0xAD);
        }

        return chr(0xF0) . chr(0x9F) . chr(0x93) . chr(0x81) . ' ' . count($result) . "\n" . implode("\n", $result);
    }
}
