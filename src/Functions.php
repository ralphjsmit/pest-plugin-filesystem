<?php

namespace RalphJSmit\PestPluginFilesystem;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;
use RalphJSmit\PestPluginFilesystem\Exceptions\FileNotFoundException;

function expectFailedAssertion(TestCase $testCase): void
{
    $testCase->expectException(AssertionFailedError::class);
}

function rm(string $path, bool $allowNonExisting = true): void
{
    if ( ! file_exists($path) ) {
        $allowNonExisting ?: throw new FileNotFoundException();

        return;
    }

    if ( is_dir($path) ) {
        rmdir_recursive($path);

        return;
    }

    unlink($path);
}

function rmdir_recursive(string $dir): void
{
    foreach (scandir($dir) as $file) {
        if ( '.' === $file || '..' === $file ) {
            continue;
        }

        if ( is_dir("$dir/$file") ) {
            rmdir_recursive("$dir/$file");
        } else {
            unlink("$dir/$file");
        }
    }
    rmdir($dir);
}
