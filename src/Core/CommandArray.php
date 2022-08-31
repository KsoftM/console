<?php

namespace KsoftM\Console\Core;

use Closure;

class CommandArray
{

    /** @var array $commands collection fo commands. */
    public array $commands = [];

    public function register(...$commands): void
    {
        array_map(function (CommandBase $cmd) {
            $this->commands[] = $cmd;
        }, $commands);
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
     * Get all commands from array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}
