<?php

namespace Swoft\DocBuild;

/**
 * class App
 */
class App extends \Toolkit\Cli\App
{
    public static function create(): self
    {
        return new self();
    }
}
