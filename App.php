<?php
use Bricks\Autoload\Loader;
use Bricks\ServiceLocator\Manager as Locator;
use Doctrine\ORM\EntityManager;                                                                                
use Doctrine\ORM\Configuration;                                                                                
use Doctrine\Common\Cache\ArrayCache;
use Longman\TelegramBot\Telegram;

class App{
  public function run(){
    $locator = new Locator;
    $locator['loader'] = $this->initLoader($locator);
    $locator['config'] = $this->loadConfig($locator);
    $locator['entity_manager'] = $this->initDoctrine($locator);
    $locator['bot'] = $this->initBot($locator);

    $locator['bot']->handleGetUpdates();

    $locator['entity_manager']->flush();
  }

  /**
   * Инициализация автозагрузчика.
   *
   * @param Locator $locator Локатор служб.
   */
  public function initLoader(Locator $locator){
    $loader = new Loader;
    $loader->pref('Model', __DIR__ . '/Model');
    $loader->pref('Command', __DIR__ . '/Command');

    return $loader;
  }

  /**
   * Загрузка конфигурации.
   *
   * @param Locator $locator Локатор служб.
   */
  public function loadConfig(Locator $locator){
    return array_merge(include(__DIR__ . '/config/global.php'), include(__DIR__ . '/config/local.php'));
  }

  /**
   * Инициализация ORM.
   *
   * @param Locator $locator Локатор служб.
   */
  public function initDoctrine(Locator $locator){
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
   */
  public function initBot(Locator $locator){
    $config = $locator['config']['TelegramBot'];
    $bot = new Telegram($config['api_token'], $config['name']);
    $bot->enableMySQL($locator['config']['DataBase']);
    $bot->addCommandsPath(__DIR__ . '/Command/UserCommands/');
    $bot->locator = $locator;

    return $bot;
  }
}
