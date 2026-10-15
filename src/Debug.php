<?php

declare(strict_types=1);

namespace Gebruederheitz\Wordpress;

use Throwable;

class Debug
{
    /**
     * @param array<mixed> $context
     */
    public static function log(
        string $message = '',
        array $context = [],
        ?string $namespace = null,
    ): void {
        if (self::isDebug()) {
            $msg = '';

            if ($namespace !== null) {
                $msg .= '[' . $namespace . '] ';
            }

            $msg .= $message;

            if (!empty($context)) {
                $msg .= ' ' . json_encode($context, 128);
            }

            error_log($msg);
        }
    }

    public static function dump(
        mixed $var,
        ?string $message = null,
        ?string $namespace = null,
    ): void {
        if (self::isDebug()) {
            $msg = '';

            if ($namespace !== null) {
                $msg .= '[' . $namespace . '] ';
            }

            if ($message !== null) {
                $msg .= $message . ' ';
            }

            $msg .= PHP_EOL;
            $msg .= self::dumpToString($var);

            error_log($msg);
        }
    }

    /**
     * Captures var_dump() output as a string without ever sending it to the
     * client: finally always closes the buffer, and the discarding callback
     * covers fatal errors (e.g. a throwing __debugInfo()), after which PHP
     * would otherwise flush the partial dump into the response.
     */
    public static function dumpToString(mixed ...$values): string
    {
        ob_start(static fn(): string => '');
        try {
            foreach ($values as $value) {
                var_dump($value);
            }
        } catch (Throwable $e) {
            echo 'Dump failed: ' . $e::class . ': ' . $e->getMessage();
        } finally {
            $dump = (string) ob_get_clean();
        }

        return $dump;
    }

    /**
     * Debug output is enabled when WP_DEBUG is true or when WORDPRESS_ENV is
     * "development", so it can be switched on explicitly for any environment
     * (including staging or, temporarily, production).
     */
    public static function isDebug(): bool
    {
        return self::isWpDebugEnabled() || self::isDevelopmentEnvironment();
    }

    private static function isWpDebugEnabled(): bool
    {
        return defined('WP_DEBUG') && WP_DEBUG === true;
    }

    private static function isDevelopmentEnvironment(): bool
    {
        return defined('WORDPRESS_ENV') && WORDPRESS_ENV === 'development';
    }
}
