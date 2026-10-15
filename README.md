# Wordpress Debug Helper

_A tiny class and trait to make logging debug output simpler._

---

## Installation

via composer:
```shell
> composer require gebruederheitz/wp-debug-log
```

Make sure you have Composer autoload or an alternative class loader present.

Requires PHP >= 8.2. Version 1.x supports PHP >= 7.3, but its `Debug::dump()`
can leak dumped values into the response when dumping fails.


## Usage

This library uses `error_log()` internally to conditionally write messages to 
Wordpress' debug log file.
The single requirement for logging output is that the environment variable
`WORDPRESS_ENV` is set and does not equal the string `"production"`.

You can call the `Debug` class' static methods directly:

```php

use Gebruederheitz\Wordpress\Debug;

if (Debug::isDebug()) {
    // do dev-instance only things
}

// Writes the message to the logfile whenever WORDPRESS_ENV is set and not
// "production"
Debug::log('My first log message');
Debug::log('I\'m logging a context as well now', [$myVariable]);

// Uses output buffering to capture a var_dump() and write the result to the
// logfile
Debug::dump($myVariable);
Debug::dump($myVariable. 'This is my variable, dumped:');

// Captures var_dump() output of one or more values as a string. The output
// buffer is always closed and discarded, so a failing dump never leaks into
// the response.
$dump = Debug::dumpToString($myVariable, $anotherVariable);
```

For a more verbose output from different modules, you can use the trait provided,
which will prepend your log messages with a namespace:

```php
use Gebruederheitz\Wordpress\withDebug;

class MyClass {
    use withDebug;
    
    public function doSomething($arg = 42): void 
    {
        self::debugLog('Doing something with these args:', [$arg]);
        /*
         * Results in:
         * [MyClass] Doing something with these args: 42 
         */
         
         self::debugDump($arg);
    }
}
```

You can also set a custom namespace through a static class attribute:

```php
use Gebruederheitz\Wordpress\withDebug;

class MyClass {
    use withDebug;
    
    protected static $debugNamespace = 'Module:NextL3vel';
    
    public function doSomething(): void 
    {
        $myVar = 42;
         self::debugDump($myVar, 'The answer to question about life, the universe, and everything is');
        /*
         * Results in:
         * [Module:NextL3vel] The answer to question about life, the universe, and everything is 
         * (int)42 
         */
    }
}
```

## Development

### Dependencies

 - [asdf](https://asdf-vm.com/) with the PHP and Node.js versions from
   `.tool-versions`
 - [Composer 2.x](https://getcomposer.org)
 - [go-task](https://taskfile.dev/)

### Tasks

```shell
# Lint with prettier, phpstan & phpcs (also runs in CI)
> task lint

# Format all files in src/ and tests/ with prettier
> task prettier

# Create a tagged release to publish a new version of the package
> task release
```

The lock file is resolved for the lowest supported PHP version
(`config.platform.php` in `composer.json`), so raise that together with the
`php` requirement.
