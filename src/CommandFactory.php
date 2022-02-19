<?php

namespace KsoftM\Console;

use Closure;
use KsoftM\Console\Core\CommandArray;
use KsoftM\Console\Core\CommandBase;
use KsoftM\Console\Core\ConsoleColorInfo;

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
        $action = function () {
            foreach ($this->commands as $cmd) {
                if (!$cmd instanceof CommandBase) continue;
                $len = strlen($cmd->getDescription()) + 2;
                $data = ConsoleColorInfo::new(ucfirst(strtolower($cmd->getTitle()) . ':'))
                    ->textColor(ConsoleColorInfo::TEXT_COLOR_LIGHT_BLUE)
                    ->echo()
                    ->space()
                    ->new(sprintf("  % -15s", 'Description'))
                    ->textColor(ConsoleColorInfo::TEXT_COLOR_ORANGE)
                    ->echo()
                    ->new(sprintf("% ${len}s", ucfirst(strtolower($cmd->getDescription()))))
                    ->echo()
                    ->space();

                $len = strlen($cmd->getFormat()) + 2;
                $data
                    ->new(sprintf("  % -15s", 'Command'))
                    ->textColor(ConsoleColorInfo::TEXT_COLOR_ORANGE)
                    ->echo()

                    ->new(sprintf("% ${len}s", $cmd->getFormat()))
                    ->textColor(ConsoleColorInfo::TEXT_COLOR_GREEN)
                    ->echo()
                    ->space(2);

                if (!$cmd instanceof CommandExtra) continue;

                $extra = $cmd->extras->getCommands();

                $data->new("  COMMANDS")
                    ->textColor(ConsoleColorInfo::TEXT_COLOR_ORANGE)
                    ->echo()
                    ->space();

                foreach ($extra as $extra) {
                    if (!$extra instanceof CommandBase) continue;

                    $len = strlen($extra->getId()) + 4;

                    $data
                        ->new(sprintf("%' +${len}s", $extra->getId()))
                        ->textColor(ConsoleColorInfo::TEXT_COLOR_GREEN)
                        ->echo();
                    $len = strlen($extra->getDescription()) + 13;

                    $data
                        ->new(sprintf("%' +${len}s", ucfirst(strtolower($extra->getDescription()))))
                        ->echo()
                        ->space();
                }
                $data->space();
            }
        };

        $help = Command::new('-h', 'Help', 'List the Functions of ', '-h', $action);
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
        $cmd = array_shift($args) ?? "-h";

        echo PHP_EOL;

        if ($this->haveCommand($cmd)) {
            $action = $this->invoke($cmd, fn () => $action, $args);
        }

        echo $action;
    }
}
