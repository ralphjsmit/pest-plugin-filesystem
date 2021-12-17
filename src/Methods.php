<?php

namespace RalphJSmit\PestPluginFilesystem;

trait Methods
{
    public function example(string $name)
    {
        $this->assertNotEmpty($name);

        return $this;
    }
}
