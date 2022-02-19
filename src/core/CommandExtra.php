<?php

namespace KsoftM\console\core;

use Closure;

class CommandExtra extends CommandBase
{
    /** @var CommandArray $extras extra short hand methods. */
    public CommandArray $extras;

    /**
     * Class constructor.
     */
    public function __construct($id, $description, $format, $action)
    {
        parent::setId($id)
            ->setDescription($description)
            ->setFormat($format)
            ->setAction($action);
        $this->extras = new CommandArray;
    }

    public static function new(
        string $id,
        string $description,
        string $format,
        Closure $action
    ) {
        return new CommandExtra($id, $description, $format, $action);
    }
}
