<?php


namespace Bashka\Taskbot;

use Bricks\ServiceLocator\Manager as Locator;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * Class TaskBot
 */
class TaskBot extends Telegram
{
    /**
     * @var Locator
     */
    public $locator;

    /**
     * TaskBot constructor.
     * @param string $apiKey
     * @param string $botName
     * @param Locator $locator
     * @throws TelegramException
     */
    public function __construct(string $apiKey, string $botName, Locator $locator)
    {
        $this->locator = $locator;
        parent::__construct($apiKey, $botName);
    }
}