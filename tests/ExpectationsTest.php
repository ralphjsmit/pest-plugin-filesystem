<?php

use function RalphJSmit\PestPluginFilesystem\expectFailedAssertion;

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
