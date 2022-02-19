<?php

namespace KsoftM\console\core;

use Closure;

class CommandFactory extends CommandArray
{
    private static CommandFactory $instance;
    public static function getInstance(): CommandFactory
    {
        if (empty(self::$instance)) {
            self::$instance = new CommandFactory();
        }
        return self::$instance;
    }

    /**
     * Class constructor.
     */
    private function __construct()
    {
    }

    public function registerHelp(): void
    {
        $action = function () use (&$data) {
            $data = [];
            array_map(function (CommandBase $cmd) use (&$data) {
                $data[] = sprintf(
                    "%s COMMAND\n--------------------------------------\ncommand - '%s'\ndescription - %s",
                    strtoupper($cmd->getId()),
                    $cmd->getFormat(),
                    ucfirst($cmd->getDescription())
                );

                if ($cmd instanceof CommandExtra) {
                    $data[] = "\nOPTIONS FOR " . strtoupper($cmd->getId());
                    array_map(function (CommandBase $cmd) use (&$data) {
                        $data[] = sprintf(
                            "'%s' - %s",
                            ucfirst($cmd->getId()),
                            ucfirst($cmd->getDescription())
                        );
                    }, $cmd->extras->getCommands());
                }
                $data[] = PHP_EOL;
            }, $this->commands);

            return is_array($data) ? trim(implode(PHP_EOL, $data)) : 'No Functions!';
        };

        $help = Command::new('-h', 'List the Functions of ', '-h', $action);
        $this->register($help);
    }

    public function invoke(string $id, Closure $action = null, array $data = []): mixed
    {
        $out = '';

        array_map(function (CommandBase $cmd) use ($id, &$out, $action, $data) {
            if ($cmd->getId() === $id) {
                if ($cmd instanceof CommandExtra) {
                    $out = $cmd->invokeOrElse(null, [$data[0] ?? '']);

                    if (count($data) > 0) {
                        foreach ($data as $arg) {
                            $out .= $cmd->extras->getOrElse([$arg], $data[0]);
                        }
                    }
                } else {
                    $out = $cmd->invokeOrElse($action, $data);
                }
            }
        }, $this->commands);

        return $out;
    }

    public function run(array $args)
    {
        sort($this->commands);
        $action = 'Invalid Command!';
        $cmd = array_shift($args);

        if ($this->haveCommand($cmd)) {
            $action = $this->invoke($cmd, fn () => $action, $args);
            // $action .= PHP_EOL . $cmd->extras->run($data);
        }

        echo PHP_EOL;
        echo $action;
        echo PHP_EOL;
    }
}
