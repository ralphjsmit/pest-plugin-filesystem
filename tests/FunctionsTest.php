<?php

use PHPUnit\Framework\AssertionFailedError;

use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;
use function RalphJSmit\PestPluginFilesystem\rmdir_recursive;

it('can correctly expect exceptions', function () {
    // The expectFailedAssertion() silences any failing exception
    expect(function () {
        expectFailedAssertion($this);
        expect(true)->toBeFalse();
    });

    // The expectFailedAssertion() doesn't silence any failing exception
    expect(function () {
        expect(true)->toBeFalse();
    })->toThrow(AssertionFailedError::class);
});

it('can recursively remove folders', function () {
    if ( file_exists(__DIR__ . '/to-be-deleted-folder/') ) {
        rmdir_recursive(__DIR__ . '/to-be-deleted-folder/child');
    }

    mkdir(__DIR__ . '/to-be-deleted-folder/child', 0777, true);
    file_put_contents(__DIR__ . '/to-be-deleted-folder/hello.txt', 'Hello!');
    file_put_contents(__DIR__ . '/to-be-deleted-folder/child/hello.txt', 'Hello!');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->toBeString()->toExist();

    rmdir_recursive(__DIR__ . '/to-be-deleted-folder');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->not->toExist();
});
