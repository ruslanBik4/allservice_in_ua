<?php

class GoBridge
{
    /**
     * @var string
     */
    protected $url = 'http://allservice.in.ua/isenka/';

    /**
     * @var resource
     */
    protected $ch;

    /**
     * @var string
     */
    protected $query_string;

    /**
     * @param string $filename
     * @throws Exception
     */
    public function __construct($filename)
    {
        $this->validate($filename);

        $this->url .= $filename . '.php?';

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Добавить префикс GET запроса.
     *
     * @param string $key
     */
    public function setQueryString($key)
    {
        $this->validate($key);

        $this->query_string = $key . '=';
    }

    /**
     * Выполнить запрос.
     *
     * @param string $command
     * @return array
     */
    public function execute($command)
    {
        $url = $this->url . $this->query_string . urlencode($command);

        curl_setopt($this->ch, CURLOPT_URL, $url);

        return $this->parseResult(curl_exec($this->ch));
    }

    /**
     * Проверить, чтоб значение было строкой состоящей только из букв.
     *
     * @param string $input
     * @throws Exception
     */
    protected function validate($input)
    {
        if (!preg_match('/^([A-z]+)$/', $input)) {
            throw new Exception('Неправильный формат имени файла. Можно использовать только буквы.');
        }
    }

    /**
     * Декодировать результат из JSON'а и вернуть в виде массива.
     *
     * @param $result
     * @return array|mixed
     */
    protected function parseResult($result)
    {
        $result = json_decode($result, true);

        if (!empty($result)) {
            foreach ($result as $res) {
                $parsed[] = json_decode($res, true);
            }
        }

        return $parsed;
    }
}