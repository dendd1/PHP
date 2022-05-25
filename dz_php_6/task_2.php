<?php
echo '<h2>Задание B</h2><br>';
    for ($k = 1; $k < 8; $k++){
        $fileNumber = '0' . $k;
        //Формат имен файлов
        $ansName = "0" . $fileNumber . ".ans";
        $datName = "0" . $fileNumber . ".dat";

        //Загрузка из файла
        $lines = file("B\\" . $datName, FILE_IGNORE_NEW_LINES);

        $nodes = array();

        //Считывание параметров меню
        foreach ($lines as $line) {
            $id = explode(' ', $line)[0];
            $name = explode(' ', $line)[1];
            $leftValue = explode(' ', $line)[2];
            $rightValue = explode(' ', $line)[3];
            $nodes[] = new Node($id, $name, $leftValue, $rightValue);
        }
        //Вычисление максимального значения среди элементов дерева
        $maxValue = count($nodes) * 2;
        $fileResult = "";
        //Алгоритм обхода дерева
        $level = 0;
        for ($i = 1; $i <= $maxValue; $i++) {
            foreach ($nodes as $node) {
                if ($node->leftValue == $i) {
                    $level++;
                    //За каждый уровень добавляем -
                    for ($j = 1; $j < $level; $j++) {
                        $fileResult .= "-";
                    }
                    $fileResult .= $node->name . "<br>";
                    break;
                }
                else if ($node->rightValue == $i){
                    $level--;
                    break;
                }
            }
        }

        //Преобразование входных данных в Html сущности
        $ans =  htmlentities(file_get_contents("B\\" . $ansName));
        $dat =  htmlentities(file_get_contents("B\\" . $datName));
        //Замена переходов строки
        $ans = str_replace("\n", "<br>", $ans); // данные из файла ответов
        $dat = str_replace("\n", "<br>", $dat); // данные из файла тестов
        echo "$k TEST-----------------------------------------------------------------------------------NEXT<br>";
        echo "Входные данные:<br> $dat<br><br><br>Ответ:<br> $ans<br><br>Ответ программы:<br> $fileResult<br>";

    }
class Node
{
    public $id;
    public $name;
    public $leftValue;
    public $rightValue;

    function __construct($id, $name, $leftValue, $rightValue) {
        $this->id = $id;
        $this->name = $name;
        $this->leftValue = $leftValue;
        $this->rightValue = $rightValue;
    }
}