<?php

namespace RalphJSmit\PestPluginFilesystem;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\TestCase;

function expectFailedAssertion(TestCase $testCase): void
{
    $testCase->expectException(AssertionFailedError::class);
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
