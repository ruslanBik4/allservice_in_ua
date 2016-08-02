<?php

/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 01.08.2016
 * Time: 23:13
 */
class formCreatorFromJsonClass
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
            $array[$key] = json_decode($value, true);
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
            foreach ($value as $valueKey => $valueValue){
                switch ($valueKey){
                    case 'html_name':
                        // Если такое db_field_name ранее встречалось, выходим на уровень первого foreach
                        if(in_array($valueValue, $this->inputNamesArray))
                        {
                            break 3;
                        }
                        $name = $valueValue;
                        $this->inputNamesArray[] = $name;
                        break;
                    case 'db_table_name':
                        $tableName = $valueValue;
                        break;
                    case 'html_class':
                        $class = $valueValue;
                        break;
                    case 'html_id':
                        $id = $valueValue;
                        break;
                    case 'html_type':
                        $type = $valueValue;
                        break;
                    case 'label':
                        $label = $valueValue;
                        break;
                }
            }
            // Создаем скрытый input для хранения названия таблицы
            // Нужен ли for в label?
            $result.= "<label for='{$id}'>{$label}</label><br>";
            // Главный input
            $result.= "<input type = '{$type}' name = '{$tableName}:{$name}' class = '{$class}' id = '{$id}'><br>";
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