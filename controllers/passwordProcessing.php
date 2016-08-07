<?php

class passwordProcessing
{

    /**
     * Метод принимает пароль от пользователя,
     * хешируют его и возвращает
     * @param $pass
     * @return bool|string
     */
    public static function encryptPass($pass)
    {
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 14]);
        return $hashedPassword;
    }

    /**
     * Методи принимает пароль от пользователя,
     * проводит сверку с захешированным паролем в БД
     * @param $pass
     * @param $hash
     * @return bool
     */
    public static function verificationPassAndHash($pass, $hash){
        $result = password_verify($pass, $hash);
        return $result;
    }
}