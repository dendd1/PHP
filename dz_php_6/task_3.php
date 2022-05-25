<?php
echo '<h2>Задание С</h2><br>';
for ($k = 1; $k < 7; $k++) {
    $fileNumber = '0' . $k;
    //Формат имен файлов
    $ansName = "0" . $fileNumber . ".ans";
    $datName = "0" . $fileNumber . ".dat";
    //Загрузка из файла
    $lines = file("C\\" . $datName, FILE_IGNORE_NEW_LINES);

    $banners = array();
    $fileResult = "";
    //Считывание индификатора рекламы и веса
    foreach ($lines as $line) {
        $name = explode(' ', $line)[0];
        $count = explode(' ', $line)[1];

        $banners[] = new Banner($name, $count);
    }
    $count = 0;
    foreach ($banners as $banner) {
        $count += $banner->count;
    }
    //Вычисление пропорции для каждого баннера
    foreach ($banners as $banner) {
        $banner->proportion = 1.0 / ($count / $banner->count);
        $fileResult .= $banner->name . " " . round($banner->proportion, 6) . "<br>";
    }
    //Преобразование входных данных в Html сущности
    $ans = htmlentities(file_get_contents("C\\" . $ansName));
    $dat = htmlentities(file_get_contents("C\\" . $datName));
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
    public $proportion;

    function __construct($name, $count) {
        $this->name = $name;
        $this->count = $count;
        $this->proportion = 0;
    }
}