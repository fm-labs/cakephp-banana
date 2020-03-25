<?php
declare(strict_types=1);

namespace Banana\Event;

use Cake\Event\EventManager;

/**
 * LocalEventManager
 *
 * Behaves like Cake's built-in event manager, but prevent bubble up events to the the global event manager
 */
class LocalEventManager extends EventManager
{
    /**
     * @var \Cake\Event\EventManager
     */
    protected static $_generalLocalManager = null;

    /**
     * Override instance call
     * Simply generate a new global event manager on every invocation
     */
    public static function instance($manager = null)
    {
        static::$_generalLocalManager = new LocalEventManager();
        static::$_generalLocalManager->_isGlobal = true;

        return static::$_generalLocalManager;
    }

    public function __debugInfo(): array
    {
        $properties = parent::__debugInfo();
        $properties['_generalLocalManager'] = '(object) EventManager';

        return $properties;
    }
}
