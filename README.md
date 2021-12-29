# Handy filesystem helpers for your Pest tests

Testing with Pest is an absolute pleasure, but sometimes you just need that little bit extra. In my case, that extra was a set of expectations and functions to interact with the filesystem and make assertions on it. Below I made a list with all the helpers currently available. Enjoy!

[![Run Tests](https://github.com/ralphjsmit/pest-plugin-filesystem/actions/workflows/tests.yml/badge.svg?event=push)](https://github.com/ralphjsmit/pest-plugin-filesystem/actions/workflows/tests.yml)

## Contents

1. Expectations
    1. [expect(...)->toHaveContents()](#expect-tohavecontents)
    2. [expect(...)->toExist()](#expect-toexist)
    3. [expect(...)->toHaveNamespace()](#expect-tohavenamespace)
2. Higher-order expectations
    1. [expect(...)->contents->toBe(...)](#expect-contents-tobe)
3. Functions
    1. [rm($path, $allowNonExisting)](#rmpath-allownonexisting)
    2. [rmdir_recursive($dir)](#rmdir_recursivedir)
    3. [contents($path)](#contentspath)
    4. [expectFailedAssertion()](#expectfailedassertion)

## Expectations

### expect(...)->toHaveContents()

Test whether a file has certain contents:

```php
file_put_contents(__DIR__ . '/tmp/fileA.php', 'I\'m a test file!');
file_put_contents(__DIR__ . '/tmp/fileB.php', 'I\'m a second test file!');

expect(__DIR__ . '/tmp/fileA.php')->toHaveContents('I\'m a test file!');
expect(__DIR__ . '/tmp/fileB.php')->not->toHaveContents('I\'m a test file!');
```

### expect(...)->toExist()

Test whether a file exists:

```php
expect(__DIR__ . '/tmp/fileA.php')->toExist();
```

### expect(...)->toHaveNamespace()

Test whether a file has a certain namespace (PHP-only):

```php
expect(__DIR__ . '/tmp/fileA.php')->toHaveNamespace('App\Models');
```

## Higher-order expectations

### expect(...)->contents->toBe(...)

Make assertions on the content of a file:

```php
file_put_contents(__DIR__ . '/tmp/fileA.php', 'I\'m a test file!');

expect(__DIR__ . '/tmp/fileA.php')
    ->contents->toBe('I\'m a test file!')
    ->toContain('test file')
    ->not->toContain('Hello world');
```

## Functions

### rm($path, $allowNonExisting)

Completely remove a file or directory.

```php
use function RalphJSmit\PestPluginFilesystem\rm;

rm('/tmp'); // Make sure that this file or directory doesn't exist

file_put_contents(__DIR__ . '/tmp/fileA.php', 'I\'m a test file!');
file_put_contents(__DIR__ . '/tmp/fileB.php', 'I\'m a second test file!');

// Other code
```

### rmdir_recursive($dir)

This function recursively deletes a folder, including its contents. Internally this is used by the `rm()` function

```php
use function RalphJSmit\PestPluginFilesystem\rmdir_recursive;

rmdir_recursive('/tmp'); // Recursively remove this directory
```

### contents($path)

Gets the file contents of a certain file. Its simply a shorter wrapper for `file_get_contents()`.

```php
use function RalphJSmit\PestPluginFilesystem\contents;

$contents = contents(__DIR__ . '/tmp/fileA.php');

expect(
    contents(__DIR__ . '/tmp/fileA.php')
)->toBe(
    contents(__DIR__ . '/tmp/fileB.php')
);
```

### expectFailedAssertion()

> Note that this helper will be added to another package soon and thus be removed here.

Expect an failed assertion. Helpful for testing your own custom Pest assertions.

```php
use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;

expectFailedAssertion();
expect(true)->toBe(false);

// This test will pass
```

```php
// Somewhere
expect()->extend('toBeHello', function () {
    return $this->toBe('Hello there');
});

// In your test
use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;

expect('Hello there')->toBeHello(); // This will pass

expectFailedAssertion();
expect('Bye')->toBeHello(); // This will pass

expectFailedAssertion();
expect('Hello there')->toBeHello(); // This will fail
```
