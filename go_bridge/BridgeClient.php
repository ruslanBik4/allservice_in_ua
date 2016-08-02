<?php

abstract class BridgeClient
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
    protected function filename()
    {
        // TODO: Вернуть строку с именем файла (query, go и т.п.)
    }
}