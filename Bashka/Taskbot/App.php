<?php

namespace Bashka\Taskbot;

use Bricks\ServiceLocator\Manager as Locator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use Doctrine\ORM\ORMException;

/**
 * Class App
 */
class App implements AppInterface
{
    /**
     * @var string[]
     */
    private $configs;

    /**
     * App constructor.
     * @param array $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
    }

    /**
     * @throws ORMException
     * @throws TelegramException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function run(): void
    {
        $locator = new Locator;
        $locator['config'] = $this->loadConfig($locator);
        $locator['entity_manager'] = $this->initDoctrine($locator);
        $locator['bot'] = $this->initBot($locator);

        $locator['bot']->handleGetUpdates();

        $locator['entity_manager']->flush();
    }

    /**
     * Загрузка конфигурации.
     *
     * @param Locator $locator Локатор служб.
     * @return array
     */
    public function loadConfig(Locator $locator): array
    {
        $includeList = [];

        foreach ($this->configs as $config) {
            $includeList = array_merge($includeList, include($config));
        }

        return $includeList;
    }

    /**
     * Инициализация ORM.
     *
     * @param Locator $locator Локатор служб.
     * @return EntityManager
     * @throws ORMException
     */
    public function initDoctrine(Locator $locator): EntityManager
    {
        $config = $locator['config']['DataBase'];

        $cache = new ArrayCache;
        $emConfig = new Configuration;
        $emConfig->setMetadataCacheImpl($cache);
        $emConfig->setQueryCacheImpl($cache);
        $driverImpl = $emConfig->newDefaultAnnotationDriver(__DIR__ . '/Model');
        $emConfig->setMetadataDriverImpl($driverImpl);
        $emConfig->setProxyDir(__DIR__ . '/Model');
        $emConfig->setProxyNamespace('Model\Proxies');
        $emConfig->setAutoGenerateProxyClasses(true);

        return EntityManager::create([
            'driver' => 'pdo_mysql',
            'host' => $config['host'],
            'user' => $config['user'],
            'password' => $config['password'],
            'dbname' => $config['database'],
            'charset' => 'utf8mb4',
        ], $emConfig);
    }

    /**
     * Инициализация бота.
     *
     * @param Locator $locator Локатор служб.
     * @return Telegram
     * @throws TelegramException
     */
    public function initBot(Locator $locator): Telegram
    {
        $config = $locator['config']['TelegramBot'];
        $bot = new TaskBot($config['api_token'], $config['name'], $locator);
        $bot->enableMySQL($locator['config']['DataBase']);
        $bot->addCommandsPath(__DIR__ . '/Command/UserCommands/');

        return $bot;
    }
}
