<?php

namespace Drupal\dino\Jurassic;

use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;

class RoarGenerator
{
    const STORE_COLLECTION_NAME = 'dino';
    const STORE_KEY_PREFIX = 'roar_';

    private $useCache;

    /**
     * @var KeyValueFactoryInterface
     */
    private $keyValueFactory;

    function __construct($useCache, KeyValueFactoryInterface $keyValue)
    {
        $this->useCache = $useCache;
        $this->keyValueFactory = $keyValue;
    }

    public function roar($length)
    {
        $key = self::STORE_KEY_PREFIX . $length;
        $keyValueStore = $this->keyValueFactory->get(self::STORE_COLLECTION_NAME);

        if ($this->useCache && $keyValueStore->get($key)) {
            return $keyValueStore->get($key);
        }

        sleep(2);
        $string = 'hello from din' . str_repeat('o', $length) . '!';

        if ($this->useCache) {
            $keyValueStore->set($key, $string);
        }

        return $string;
    }
}
