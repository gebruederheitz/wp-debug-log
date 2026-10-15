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

    public static function isDebug(): bool
    {
        return self::isNotProduction();
    }

    private static function isNotProduction(): bool
    {
        return defined('WORDPRESS_ENV') && WORDPRESS_ENV !== 'production';
    }
}
