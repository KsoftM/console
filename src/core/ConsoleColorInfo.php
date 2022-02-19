<?php

namespace KsoftM\console\core;

use KsoftM\Contract\ConsoleColorInfoInterface;

class ConsoleColorInfo
{
    /** @var array $prefix prefix of the console start. */
    protected array $prefix;

    /** @var array $suffix suffix of the console end. */
    protected array $suffix;

    /** @var string $data data of the console. */
    protected string $data;

    /** @var string $autoReset automatically reset the console. */
    protected string $autoReset;



    protected const RESET = "\e[0m";
    protected const ERASE = "\e[H\e[2J";

    public const STYLE_NORMAL = "0;";
    public const STYLE_BOLD = "1;";
    public const STYLE_ITALIC = "3;";
    public const STYLE_UNDERLINED = "4;";
    public const STYLE_STRIKE = "9;";

    // Regular Colors
    public const TEXT_COLOR_BLACK = "30";
    public const TEXT_COLOR_RED = "31";
    public const TEXT_COLOR_GREEN = "32";
    public const TEXT_COLOR_YELLOW = "33";
    public const TEXT_COLOR_BLUE = "34";
    public const TEXT_COLOR_PURPLE = "35";
    public const TEXT_COLOR_CYAN = "36";
    public const TEXT_COLOR_WHITE = "37";
    public const TEXT_COLOR_LIGHT_PURPLE = "38;2;200;0;200";
    public const TEXT_COLOR_TEAL = "38;2;0;225;221";
    public const TEXT_COLOR_ORANGE = "38;2;225;153;0";
    public const TEXT_COLOR_LIGHT_GREEN = "38;2;136;255;0";
    public const TEXT_COLOR_LIGHT_BLUE = "38;2;120;172;255";
    public const TEXT_COLOR_DARK_BLUE = "38;2;72;0;255";
    public const TEXT_COLOR_ROSY_PINK = "38;2;255;0;162";
    public const TEXT_COLOR_BROWN = "38;2;135;82;62";
    public const TEXT_COLOR_FOREST_GREEN = "38;2;62;135;81";
    public const TEXT_COLOR_BANANA_YELLOW = "38;2;240;238;113";
    public const TEXT_COLOR_DARK_RED = "38;2;145;40;16";
    public const TEXT_COLOR_LIGHT_PINK = "38;2;255;153;240";
    public const TEXT_COLOR_DARK_PURPLE = "38;2;123;53;240";

    // background color
    public const BACKGROUND_COLOR_BLACK = "40";
    public const BACKGROUND_COLOR_RED = "41";
    public const BACKGROUND_COLOR_GREEN = "42";
    public const BACKGROUND_COLOR_YELLOW = "43";
    public const BACKGROUND_COLOR_BLUE = "44";
    public const BACKGROUND_COLOR_PURPLE = "45";
    public const BACKGROUND_COLOR_CYAN = "46";
    public const BACKGROUND_COLOR_WHITE = "47";
    public const BACKGROUND_COLOR_LIGHT_PURPLE = "48;2;200;0;200";
    public const BACKGROUND_COLOR_TEAL = "48;2;0;225;221";
    public const BACKGROUND_COLOR_ORANGE = "48;2;225;153;0";
    public const BACKGROUND_COLOR_LIGHT_GREEN = "48;2;136;255;0";
    public const BACKGROUND_COLOR_LIGHT_BLUE = "48;2;120;172;255";
    public const BACKGROUND_COLOR_DARK_BLUE = "48;2;72;0;255";
    public const BACKGROUND_COLOR_ROSY_PINK = "48;2;255;0;162";
    public const BACKGROUND_COLOR_BROWN = "48;2;135;82;62";
    public const BACKGROUND_COLOR_FOREST_GREEN = "48;2;62;135;81";
    public const BACKGROUND_COLOR_BANANA_YELLOW = "48;2;240;238;113";
    public const BACKGROUND_COLOR_DARK_RED = "48;2;145;40;16";
    public const BACKGROUND_COLOR_LIGHT_PINK = "48;2;255;153;240";


    protected const PREFIX_STYLE = '1-P-S';
    protected const PREFIX_TEXT_COLOR = '2-P-T-C';
    protected const PREFIX_BG_COLOR = '3-P-B-C';

    protected const SUFFIX_BEGIN = '1-S-B';
    protected const SUFFIX_COMMAND = '2-S-C';
    protected const SUFFIX_END = '3-S-E';
    protected const SUFFIX_END_LINE = '4-S-E-L';

    /**
     * Class constructor.
     */
    protected function __construct(string $data, bool $autoReset, bool $autoNewLine, array $prefix = [], array $suffix = [])
    {
        if ($autoNewLine) {
            $this->endL();
        }
        $this->data = $data;
        $this->autoReset = $autoReset;
        $this->prefix = $prefix;
        $this->suffix = $suffix;
    }

    public function echo(): self
    {
        if ($this->autoReset) {
            $this->reset();
        }

        echo $this->__toString();

        return $this;
    }

    public function __toString(): string
    {
        $out = sprintf(
            "\e[%sm%s%s",
            implode(';', $this->prefix),
            $this->data,
            implode('', $this->suffix),
        );
        return $out;
    }

    public static function new(string $data, bool $autoReset = true, bool $autoNewLine = true): ConsoleColorInfo
    {
        return new ConsoleColorInfo($data, $autoReset, $autoNewLine);
    }

    public function reset()
    {
        $this->suffix[] = self::RESET;
        return $this;
    }

    public function endL(bool $autoReset = true)
    {
        if ($autoReset) {
            $this->reset();
        }
        $this->suffix[] = PHP_EOL;

        return $this;
    }

    public function erase()
    {
        $this->suffix[] = self::ERASE;

        return $this;
    }

    public function style($style)
    {
        $this->prefix[self::PREFIX_STYLE] = $style;

        return $this;
    }

    public function textColor($color)
    {
        $this->prefix[self::PREFIX_TEXT_COLOR] = $color;

        return $this;
    }

    public function backgroundColor($color)
    {
        $this->prefix[self::PREFIX_BG_COLOR] = $color;

        return $this;
    }
}
