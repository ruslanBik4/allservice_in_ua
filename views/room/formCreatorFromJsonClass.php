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

    private $queryString = [];
    /**
     * Принимаем json, приобразуем у массив и записываем в $this->arrayFromJson
     * FormCreatorFromJsonClass constructor.
     * @param array $array
     */
    public function __construct(array $array, $queryString = null)
    {
/*
        foreach ($array as $key => $value){
            $array[$key] = json_decode($value, true);
        }
*/
        if ($queryString)
            $this->queryString = $queryString;


        $this->arrayFromJson = $array;
    }


    /**
     * Функция принимает путь к обработчику, возвращает готовую форму
     * Если обработчик не передан, считаем обработчиком текущую файл
     * @param null $obrabotchik
     * @return string
     */
    public function formCreation($obrabotchik = null){
        $print = "<form method='post' action='{$obrabotchik}' onSubmit='return validate(this)'>";
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

        $arrFields = $this->arrayFromJson;
        // Перебираем данные
        foreach ($arrFields as $number => $field){

            // Флажок пропуска итерации цикла
            $skipInputLabelCreation = 0;

            foreach ($field as $attribite => $value){
                switch ($attribite){
                    case 'html_name':

                        // Если такое db_field_name ранее встречалось, устанавливаем $skipInputLabelCreation = 1
                        if(in_array($value, $this->inputNamesArray))
                        {
                            $skipInputLabelCreation = 1;
                        }
                        $this->inputNamesArray[] = $name = $value;
                        break;
                    case 'db_table_name':
                        $tableName = $value;
                        break;
                    case 'html_class':
                        $class = $value;
                        break;
                    case 'html_id':
                        $id = $value;
                        break;
                    case 'html_type':
                        $type = $value;
                        break;
                    case 'label':
                        $label = $value;
                        if($label == 'label'){
                            unset($label);
                        }
                        break;
                }
            }


            if(!$skipInputLabelCreation)
            {
                $valueInput = ( $this->queryString[$name] ? "value='".$this->queryString[$name] . "'" : '');
                // Создаем скрытый input для хранения названия таблицы

                if(isset($label)){
                    $result.= "<label for='{$id}'>{$label}</label><br>";
                }
                // Главный input
                $result.= "<input type = '{$type}' name = '{$tableName}__{$name}' class = '{$class}' id = '{$id}' $valueInput /><br>";
            }
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