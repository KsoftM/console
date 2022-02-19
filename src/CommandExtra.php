<?php

namespace KsoftM\Console;

use Closure;
use KsoftM\Console\Core\CommandArray;
use KsoftM\Console\Core\CommandBase;

class CommandExtra extends CommandBase
{
    /** @var CommandArray $extras extra short hand methods. */
    public CommandArray $extras;

    /**
     * Class constructor.
     */
    public function __construct($id, $title, $description, $format, $action)
    {
        parent::setId($id)
            ->setTitle($title)
            ->setDescription($description)
            ->setFormat($format)
            ->setAction($action);
        $this->extras = new CommandArray;
    }

    public static function new(
        string $id,
        string $title,
        string $description,
        string $format,
        Closure $action
    ) {
        return new CommandExtra($id, $title, $description, $format, $action);
    }
}
