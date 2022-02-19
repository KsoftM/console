<?php

namespace KsoftM\console\core;

use Closure;

class Command extends CommandBase
{
    public static function new(
        string $id,
        string $title,
        string $description,
        string $format,
        Closure $action
    ): CommandBase {
        return new Command($id, $title, $description, $format, $action);
    }
}
