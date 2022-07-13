<?php

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps -- I disagree: Traits should be camelCase, not PascalCase.

declare(strict_types=1);

namespace Gebruederheitz\Wordpress;

trait withDebug
{
    protected static function debugLog(
        string $message = '',
        array $context = []
    ): void {
        $namespace = self::getNamespace();
        Debug::log($message, $context, $namespace);
    }

    protected static function debugDump($var, string $message = null): void
    {
        $namespace = self::getNamespace();
        Debug::dump($var, $message, $namespace);
    }

    private static function getNamespace(): string
    {
        $className = explode('\\', static::class);

        return static::$debugNamespace ?: array_pop($className);
    }
}
