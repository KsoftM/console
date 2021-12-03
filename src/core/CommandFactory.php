<?php

namespace ksoftm\console\core;

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
        array_map(function (CommandBase $cmd) use ($id, &$action, $data) {
            if ($cmd->getId() === $id) {
                if ($cmd instanceof CommandExtra) {
                    $action = $cmd->invokeOrElse($action, [array_shift($data)]);

                    if (!empty($data)) {
                        $action .= $cmd->extras->getOrElse($data, null, $data ?? []);
                    }
                } else {
                    $action = $cmd->invokeOrElse($action, $data);
                }
            }
        }, $this->commands);

        return $action;
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
