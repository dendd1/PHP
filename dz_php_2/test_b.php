<?php
//-----------------------Задание b
echo '<h2>Задание b</h2>
            <br>';
//Функция развертывания ip адреса
//Параметры:
//ip - значение ip
//return развернутый ip
function task_b($ip){
    $result="";
    $all_blocks_amount=8;
    $full_blocks_amount=0;
    $first_check_ip=explode("::",$ip);//определение участков с ::
    foreach($first_check_ip as $second_check_ip){
        $blocks=explode(":",$second_check_ip);
        $full_blocks_amount+=count($blocks); //количество блоков без ::
    }
    $empty_blocks_amount=$all_blocks_amount-$full_blocks_amount;//пустые блоки
    if($ip[0]==":" && $ip[1]==":"){//заполнение ip при :: в начале адреса
        for($i=0;$i<$empty_blocks_amount;$i++){
            $result.="0000:";
        }
        $empty_blocks_amount=0;
    }
    foreach($first_check_ip as $second_check_ip){//заполнение недостающими нулями
        $blocks=explode(":",$second_check_ip);
        foreach($blocks as $block){
            for($i=0;$i<(4-strlen($block));$i++){ //количество недостающих блоком суммируем
                $result.="0";
            }
            $result.=$block.":";
        }
        while($empty_blocks_amount>0){
            $result.="0000:";
            $empty_blocks_amount--;
        }
    }
    $result=substr($result, 0, -1);
    return $result;
}
$inputData = glob('B/*.dat');//загрузка файлов с входными данными
$inputAns = glob('B/*.ans');//загрузка файлов с ответами
$num=1;//счетчик теста
foreach(array_combine($inputData,$inputAns) as $input => $output){//создание массива с ключами data и данными ans
    $task_input = fopen($input, 'r');//запись отдельного задания и ответа
    $task_answer = fopen($output, 'r');
    while((!feof($task_answer)) && (!feof($task_input))){//проверка на достижение конца файла
        $input_r = trim(fgets($task_input), " \n\r\t");//запись строки входных данных
        $answer = trim(fgets($task_answer), " \n\r\t");//запись строки ответа
        if(!empty($input_r) && !empty($answer)){//вывод результата проверки
            echo "<br>Входные данные: $input_r<br>";
            $result = task_b($input_r);
            echo "<br>Тест $num: ";
            if ($answer == $result) {
                echo "Ок<br>";
            } else {
                echo "Ошибка<br>Верный ответ: $answer<br>Ответ программы: $result<br>";
            }
        }
    }
    $num++;
}