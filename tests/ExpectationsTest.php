<?php

use PHPUnit\Framework\AssertionFailedError;

use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;
use function RalphJSmit\PestPluginFilesystem\rm;

beforeEach(function () {
    if ( file_exists(__DIR__ . '/test.php') ) {
        unlink(__DIR__ . '/test.php');
    }
});

it('it can correctly determine if a file does exist', function () {
    file_put_contents(__DIR__ . '/test.php', 'I\'m a test file!');

    expect(file_exists(__DIR__ . '/test.php'))->toBeTrue();
    expect(__DIR__ . '/test.php')->toExist();

    expectFailedAssertion($this);

    expect(__DIR__ . '/test.php')
        ->not->toExist();
});

it('it can correctly determine if a file does\'t exist', function () {
    expect(file_exists(__DIR__ . '/test.php'))->toBeFalse();
    expect(__DIR__ . '/test.php')->not->toExist();

    expectFailedAssertion($this);

    expect(__DIR__ . '/test.php')->toExist();
});

test('it can correctly determine if a file has certain contents', function () {
    rm(__DIR__ . '/tmp/fileA.php');
    rm(__DIR__ . '/tmp/fileB.php');
    rm(__DIR__ . '/tmp/fileC.php');

    file_put_contents(__DIR__ . '/tmp/fileA.php', 'I\'m a test file!');
    file_put_contents(__DIR__ . '/tmp/fileB.php', 'I\'m a second test file!');
    file_put_contents(__DIR__ . '/tmp/fileC.php', 'I\'m an empty file!');

    expect(__DIR__ . '/tmp/fileA.php')->toHaveContents('I\'m a test file!');
    expect(__DIR__ . '/tmp/fileB.php')->toHaveContents('I\'m a second test file!');
    expect(__DIR__ . '/tmp/fileC.php')->not->toHaveContents('I\'m a test file!');

    expect(function () {
        expect(__DIR__ . '/tmp/fileA.php')
            ->toHaveContents('I\'m a regular file!');
    })->toThrow(AssertionFailedError::class);

    expect(function () {
        expect(__DIR__ . '/tmp/fileB.php')
            ->toHaveContents('I\'m a regular file!');
    })->toThrow(AssertionFailedError::class);

    expect(function () {
        expect(__DIR__ . '/tmp/fileC.php')
            ->not->toHaveContents('I\'m an empty file!');
    })->toThrow(AssertionFailedError::class);
});
