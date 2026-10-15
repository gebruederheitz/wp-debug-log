<?php

// phpcs:disable Squiz.Classes.ValidClassName.NotPascalCase -- I disagree: Traits should be camelCase, not PascalCase.

declare(strict_types=1);

namespace Gebruederheitz\Wordpress;

trait withDebug
{
    /**
     * @param array<mixed> $context
     */
    protected static function debugLog(
        string $message = '',
        array $context = [],
    ): void {
        $namespace = self::getNamespace();
        Debug::log($message, $context, $namespace);
    }

    protected static function debugDump(
        mixed $var,
        ?string $message = null,
    ): void {
        $namespace = self::getNamespace();
        Debug::dump($var, $message, $namespace);
    }

    private static function getNamespace(): string
    {
        if (
            property_exists(static::class, 'debugNamespace') &&
            is_string(static::$debugNamespace)
        ) {
            return static::$debugNamespace;
        }

        $className = explode('\\', static::class);

        return array_pop($className);
    }
}
