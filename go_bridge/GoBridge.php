<?php

class GoBridge
{
    /* @var string */
    protected $go_file;

    /* @var string */
    protected $command;

    /* @var array */
    protected $output = [];

    /**
     * В конструктор передается файл Go
     * Если файл не найден, будет выброшено исключение
     *
     * @param string $go_file
     * @throws Exception
     */
    public function __construct($go_file)
    {
        if (!file_exists($go_file)) {
            throw new Exception("File '{$go_file}' was not found.");
        }

        $this->go_file .= $go_file;
    }

    public function showLastCommand()
    {
        return $this->command;
    }

    /**
     * Выполнить запрос к файлу Go
     *
     * @param string|array|null $command
     * @return array
     */
    public function execute($command = null)
    {
        $this->output = [];

        $this->setCommand($command);

        exec($this->go_file . $this->command, $this->output);

        return $this->arrayOutput();
    }

    /**
     * Выполнить запрос к файлу Go через метод POST
     *
     * @return array
     */
    public function executePost()
    {
        if (isset($_POST['command'])) {
            return $this->execute($_POST['command']);
        }
    }

    /**
     * @param string $command
     * @return null
     */
    protected function setCommand($command = null)
    {
        $this->command = '';

        if (!$command) {
            return null;
        }

        if (is_array($command)) {
            foreach ($command as $arg) {
                $this->command .= ' ' . escapeshellarg($arg);
            }
        } else {
            $this->command .= ' ' . escapeshellarg($command);
        }
    }

    /**
     * @return array
     */
    protected function arrayOutput()
    {
        $array = [];

        foreach ($this->output as $value) {
            $array[] = json_decode($value, true);
        }

        return $array;
    }
}