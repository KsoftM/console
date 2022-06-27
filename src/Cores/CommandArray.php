<?php

namespace KsoftM\Console\Core;

use Closure;

class CommandArray
{

    /** @var array $commands collection fo commands. */
    public array $commands = [];

    public function register(CommandBase $command): void
    {
        $this->commands[] = $command;
    }

    public function haveCommand(string $id): bool
    {
        $have = false;
        array_map(function (CommandBase $cmd) use ($id, &$have) {
            if ($cmd->getId() === $id) {
                $have = true;
            }
        }, $this->commands);

        return $have;
    }

    public function getOrElse(array $ids, string $data, Closure $action = null): mixed
    {
        foreach ($ids as $id) {
            if ($this->haveCommand($id)) {
                array_map(function (CommandBase $cmd) use ($id, &$action, $data) {
                    if ($cmd->getId() === $id) {
                        $action .= $cmd->invokeOrElse(null, [$data]);
                    }
                }, $this->commands);
            }
        }
        return $action;
    }

    /**
     * Get the value of commands
     */
    public function getCommands()
    {
        return $this->commands;
    }
}
