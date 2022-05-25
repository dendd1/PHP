<?php
echo '<h2>Задание A</h2><br>';
for ($k = 1; $k < 5; $k++) {
    //Формат имен файлов
    $datName = "00" . $k . ".dat";
    $ansName = "00" . $k . ".ans";

    //Загрузка из файла
    $lines = file("A\\" . $datName, FILE_IGNORE_NEW_LINES);

    $banners = array();
    //Считывание входных данных
    foreach ($lines as $line) {
        //Считывание индификатора рекламы и времени
        $name = explode('        ', $line)[0];
        $time = explode('        ', $line)[1];
        $uniqueFlag = true;
        foreach ($banners as $banner) {
            //Если уже есть данный индификатор рекламы
            if ($banner->name == $name) {
                $uniqueFlag = false;
                $banner->count++;
                $newDate = date_create($time);
                //Обновление времени
                if ($newDate > $banner->time) {
                    $banner->time = $newDate;
                }
                break;
            }
        }
        //Запись новго индификатора
        if ($uniqueFlag == true) {
            $banners[] = new Banner($name, 1, $time);
        }
    }
    $fileResult = "";
    foreach ($banners as $banner) {
        $fileResult .= $banner->count . " " . $banner->name . " " . date_format($banner->time, 'd.m.Y H:i:s') . "<br>";
    }
    //Преобразование входных данных в Html сущности
    $dat = htmlentities(file_get_contents("A\\" . $datName));
    $ans = htmlentities(file_get_contents("A\\" . $ansName));
    //Замена переходов строки
    $ans = str_replace("\n", "<br>", $ans);
    $dat = str_replace("\n", "<br>", $dat);
    echo "$k TEST-----------------------------------------------------------------------------------NEXT<br>";
    echo "Входные данные:<br> $dat<br><br><br>Ответ:<br> $ans<br><br>Ответ программы:<br> $fileResult<br>";
}

class Banner
{
    public $name;
    public $count;
    public $time;

    function __construct($name, $count, $time)
    {
        $this->name = $name;
        $this->count = $count;
        $this->time = date_create($time);
    }
}
