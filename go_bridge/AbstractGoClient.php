<?php

abstract class AbstractGoClient
{
    /* @var GoBridge */
    protected $go;

    public function __construct($filePath = null)
    {
        if (!$filePath) {
            $filePath = $this->defaultPath($filePath);
        }

        $this->go = new GoBridge($filePath);
    }

    /**
     * @return string $filePath
     */
    protected function defaultPath() {}

    /**
     * @return bool
     */
    protected final function isUnix()
    {
        if (!preg_match_all('/windows/i', php_uname())) {
            return true;
        }

        return false;
    }
}