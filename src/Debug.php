<?php

declare(strict_types=1);

namespace Gebruederheitz\Wordpress;

class Debug
{
    /**
     * @param array<mixed> $context
     */
    public static function log(
        string $message = '',
        array $context = [],
        string $namespace = null
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

    /**
     * @param mixed $var
     */
    public static function dump(
        $var,
        string $message = null,
        string $namespace = null
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

            ob_start();
            var_dump($var);
            $msg .= ob_get_clean();

            error_log($msg);
        }
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
