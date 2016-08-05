<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 05.08.2016
 * Time: 11:43
 */
class debug
{
    public static function VD($data, $message = NULL){
        echo '<pre><br>Debug start '.$message.'<br>';
        var_dump($data);
        echo '<br>Debug finish<br></pre>';
    }

    public static function EC($data, $message = NULL){
        echo '<pre><br>Debug start '.$message.'<br>';
        echo($data);
        echo '<br>Debug finish<br></pre>';
    }
}

// Пример использования debug::VD($result, '$result'.__FILE__.' '.__LINE__);