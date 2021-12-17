<?php

use function RalphJSmit\PestPluginFilesystem\rmdir_recursive;

it('can recursively remove folders', function () {
    if (file_exists(__DIR__ . '/to-be-deleted-folder/')) {
        rmdir_recursive(__DIR__ . '/to-be-deleted-folder/child');
    }

    mkdir(__DIR__ . '/to-be-deleted-folder/child', 0777, true);
    file_put_contents(__DIR__ . '/to-be-deleted-folder/hello.txt', 'Hello!');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->toBeString()->toExist();

    rmdir_recursive(__DIR__ . '/to-be-deleted-folder');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->not->toExist();
});
