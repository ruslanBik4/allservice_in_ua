<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.08.2016
 * Time: 23:13
 */
class FormCreatorFromJsonClass
{
    //Весь массив данных декодированный из json
    protected $arrayFromJson;

    //Храню все значение db_field_name, что бы избежать повторений 
    protected $inputNamesArray = array();

    /**
     * Принимаем json, приобразуем у массив и записываем в $this->arrayFromJson
     * FormCreatorFromJsonClass constructor.
     * @param array $array
     */
    public function __construct(array $array)
    {
        foreach ($array as $key => $value){
            $array[$key] = json_decode($array[$key], true);
        }
        $this->arrayFromJson = $array;
    }


    /**
     * Функция принимает путь к обработчику, возвращает готовую форму
     * Если обработчик не передан, считаем обработчиком текущую файл
     * @param null $obrabotchik
     * @return string
     */
    public function formCreation($obrabotchik = null){
        $print = "<form method='post' action='{$obrabotchik}'>";
        $print.= $this->inputCreation();
        $print.= '<br><input type="submit">';
        $print.= '</form>';
        return $print;
    }

    /**
     * Формируем (скрытый input + label + input) и возвращем в виде строки
     * @return string
     */
    public function inputCreation(){
        $result = '';
        $array = $this->arrayFromJson;
        // Перебираем данные
        foreach ($array as $key => $value){
            foreach ($value as $x => $y){
                switch ($x){
                    case 'db_field_name':
                        // Если такое db_field_name ранее встречалось, выходим на уровень первого foreach
                        if(in_array($y, $this->inputNamesArray))
                        {
                            break 3;
                        }
                        $name = $y;
                        $this->inputNamesArray[] = $name;
                        break;
                    case 'db_table_name':
                        $tableName = $y;
                        break;
                    case 'html_class':
                        $class = $y;
                        break;
                    case 'html_id':
                        $id = $y;
                        break;
                    case 'html_type':
                        $type = $y;
                        break;
                    case 'label':
                        $label = $y;
                        break;
                }
            }
            // Создаем скрытый input для хранения названия таблицы
            $result.= "<input type='hidden' name='tableName[]' value='{$tableName}'><br>";
            // Нужен ли for в label?
            $result.= "<label for='{$id}'>{$label}</label><br>";
            // Главный input
            $result.= "<input type = '{$type}' name = '{$name}' class = '{$class}' id = '{$id}'><br>";
        }
        return $result;
    }

    /**
     * Геттер для arrayFromJson
     * @return array
     */
    public function getArrayFromJson()
    {
        return $this->arrayFromJson;
    }

}