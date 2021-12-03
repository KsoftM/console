<?php

namespace ksoftm\console\core;

use Closure;

class Command extends CommandBase
{
    public static function new(
        string $id,
        string $description,
        string $format,
        Closure $action
    ): CommandBase {
        return new Command($id, $description, $format, $action);
    }
}
