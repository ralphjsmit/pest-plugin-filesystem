<?php

namespace RalphJSmit\PestPluginFilesystem;

trait FirstTrait
{
    public function example(string $name)
    {
        $this->assertNotEmpty($name);

        return $this;
    }
}
