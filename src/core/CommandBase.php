<?php

namespace KsoftM\console\core;

use Closure;

abstract class CommandBase
{
    /** @var string $id command identity no. */
    protected string $id;

    /** @var string $title title of the command. */
    protected string $title;

    /** @var string $description description about the cmd. */
    protected string $description;

    /** @var string $format cmd format. */
    protected string $format;

    /** @var Closure $action action of the cmd. */
    protected Closure $action;

    /**
     * Class constructor.
     */
    public function __construct(string $id, string $title, string $description, string $format, Closure $action)
    {
        $this->id = $id;
        $this->description = $description;
        $this->format = $format;
        $this->action = $action;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the value of format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    protected function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    protected function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the value of format
     *
     * @return  self
     */
    protected function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * invoke the action
     */
    public function invokeOrElse(Closure $action = null, array $data = []): mixed
    {
        if (!empty($this->action)) {
            return call_user_func_array($this->action, $data);
        } else {
            if (!empty($action)) {
                return call_user_func_array($action, $data);
            } else {
                return sprintf("No Action for the command '%s'.", $this->getId());
            }
        }
    }

    /**
     * Set the value of action
     *
     * @return  self
     */
    protected function setAction($action)
    {
        $this->action = $action;

        return $this;
    }
}
