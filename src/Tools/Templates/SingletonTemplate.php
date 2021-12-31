<?php declare(strict_types=1);

namespace Inferred\Tools\Templates;

use Inferred\Types\T1;

class SingletonTemplate
{
    private static ?T1 $instance = null;

    public static function getInstance(): T1
    {
        if (!static::$instance) {
            self::$instance = static::createInstance();
        }
        return static::$instance;
    }

    static protected function createInstance(): T1
    {
        $instance = new T1();
        return $instance;
    }
}
