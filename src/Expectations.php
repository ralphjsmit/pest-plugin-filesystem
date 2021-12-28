<?php

namespace RalphJSmit\PestPluginFilesystem;

use Illuminate\Support\Str;
use Pest\HigherOrderExpectation;

expect()->extend('toExist', function () {
    expect(
        file_exists($this->value)
    )->toBeTrue();

    return $this;
});

expect()->extend('toHaveContents', function (mixed $contents) {
    expect(
        file_get_contents($this->value)
    )->toBe(
        $contents
    );

    return $this;
});

expect()->extend('toHaveNamespace', function (string $namespace) {
    expect(
        (string) Str::of(
            file_get_contents($this->value)
        )->after('namespace')->before(';')->trim()
    )->toBe($namespace);

    return $this;
});

expect()->extend('contents', function () {
    return new HigherOrderExpectation($this, file_get_contents($this->value));
});
