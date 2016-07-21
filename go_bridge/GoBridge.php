<?php

class GoBridge
{
    protected $go_file;
    protected $command;
    protected $output;

    /*
     * В конструктор передается файл Go.
     * Если файл не найден, будет выброшено исключение.
     */
    public function __construct($go_file)
    {
        if (!file_exists($go_file)) {
            $exception = "Файл '{$go_file}' не найден.";

            // Временное решение, т.к. в настройках Allservice не отображаются исключения
            echo $exception;

            throw new Exception($exception);
        }

        $this->go_file .= './' . $go_file;
    }

    /*
     * Выполнить запрос к файлу Go
     */
    public function execute($command = null)
    {
        $this->setCommand($command);

        exec($this->command, $this->output);

        return $this->prepareOutput();
    }

    /*
     * Задать комманду
     */
    protected function setCommand($command = null)
    {
        if (!$command) {
            $this->command = $this->go_file;
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

    /*
     * Конвертировать JSON и вернуть в виде массива
     */
    protected function prepareOutput()
    {
        $toReturn = [];

        foreach ($this->output as $value) {
            $toReturn[] = json_decode($value, true);
        }

        return $toReturn;
    }
}