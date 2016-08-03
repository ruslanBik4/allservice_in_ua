<?php

abstract class AbstractBridgeClient
{
    /**
     * @var GoBridge
     */
    protected $go_bridge;

    public function __construct()
    {
        $this->go_bridge = new GoBridge($this->filename());
    }

    /**
     * @return string
     */
    protected abstract function filename();
}