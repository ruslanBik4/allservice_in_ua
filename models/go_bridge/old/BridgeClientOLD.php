<?php

abstract class BridgeClientOLD
{
    /* @var GoBridgeOLD */
    protected $go;

    public function __construct($filePath = null)
    {
        if (!$filePath) {
            $filePath = dirname(dirname(__DIR__)) . '/isenka/';
            $filePath .= $this->defaultFile();

            if (!$this->isUnix()) {
                $filePath .= '.exe';
            }
        }

        $this->go = new GoBridgeOLD($filePath);
    }

    /**
     * @return string
     */
    protected function defaultFile()
    {
        return '';
    }

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