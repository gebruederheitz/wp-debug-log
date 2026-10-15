<?php

declare(strict_types=1);

namespace Gebruederheitz\Wordpress\Tests\PHPStan;

use Gebruederheitz\Wordpress\withDebug;

/**
 * Not shipped or autoloaded: gives PHPStan a class that uses the trait, since
 * it only analyses traits in the context of their users.
 */
final class UsesWithDebug
{
    use withDebug;

    public static function run(): void
    {
        self::debugLog('message', ['context']);
        self::debugDump(42, 'message');
    }
}
