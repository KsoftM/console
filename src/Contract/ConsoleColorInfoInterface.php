<?php

namespace KsoftM\Contract;


interface ConsoleColorInfoInterface
{
    /**
     * reset the line
     *
     * @return self
     */
    public function reset();

    /**
     * end of the line
     *
     * @param boolean $autoReset
     *
     * @return self
     */
    public function endL(bool $autoReset = true);

    /**
     * erase the line
     *
     * @return self
     */
    public function erase();

    /**
     * style of the data
     *
     * @param string $style
     *
     * @return void
     */
    public function style(string $style);

    /**
     * set the text color
     *
     * @param string $color
     *
     * @return self
     */
    public function textColor(string $color);

    /**
     * set the background color
     *
     * @param string $color
     *
     * @return self
     */
    public function backgroundColor(string $color);
}
