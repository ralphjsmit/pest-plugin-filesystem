<?php

use PHPUnit\Framework\AssertionFailedError;
use RalphJSmit\PestPluginFilesystem\Exceptions\FileNotFoundException;

use function RalphJSmit\PestPluginFilesystem\contents;
use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;
use function RalphJSmit\PestPluginFilesystem\rm;
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
    if (file_exists(__DIR__ . '/to-be-deleted-folder/')) {
        rmdir_recursive(__DIR__ . '/to-be-deleted-folder/child');
    }

    mkdir(__DIR__ . '/to-be-deleted-folder/child', 0777, true);
    file_put_contents(__DIR__ . '/to-be-deleted-folder/hello.txt', 'Hello!');
    file_put_contents(__DIR__ . '/to-be-deleted-folder/child/hello.txt', 'Hello!');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->toExist();

    rmdir_recursive(__DIR__ . '/to-be-deleted-folder');

    expect(__DIR__ . '/to-be-deleted-folder/hello.txt')
        ->not->toExist();
});

test('it can delete folders and files if they exist', function () {
    $files = [
        __DIR__ . '/tmp/folderA/fileA.txt',
        __DIR__ . '/tmp/folderA/fileB.txt',
        __DIR__ . '/tmp/folderA',
        __DIR__ . '/tmp/folderB',
        __DIR__ . '/tmp/folderB/nonexistent.txt',
    ];

    foreach ($files as $file) {
        if (file_exists($file)) {
            is_dir($file) ? rmdir_recursive($file) : unlink($file);
        }
        expect(file_exists($file))->toBeFalse();
    }

    mkdir(__DIR__ . '/tmp/folderA/', 0777, true);
    file_put_contents($files[0], 'Test file A');
    file_put_contents($files[1], 'Test file B');

    expect(file_exists($files[0]))->toBeTrue();
    expect(file_exists($files[1]))->toBeTrue();
    expect(file_exists($files[2]))->toBeTrue();
    expect(file_exists($files[3]))->toBeFalse();
    expect(file_exists($files[4]))->toBeFalse();

    rm($files[0]);
    expect(file_exists($files[0]))->toBeFalse();

    rm($files[1]);
    expect(file_exists($files[1]))->toBeFalse();

    rm($files[2]);
    expect(file_exists($files[2]))->toBeFalse();

    rm($files[3]);
    expect(file_exists($files[3]))->toBeFalse();

    expect(function () use ($files) {
        rm($files[4], false);
        expect(file_exists($files[4]))->toBeFalse();
    })->toThrow(FileNotFoundException::class);
});

test('it can return the contents of a file', function () {
    rm(__DIR__ . '/tmp/test.php');
    file_put_contents(__DIR__ . '/tmp/test.php', '<?php echo "Hello World!";');

    expect(
        contents(__DIR__ . '/tmp/test.php')
    )->toBe('<?php echo "Hello World!";');
});
