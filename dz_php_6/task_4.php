<?php
echo '<h2>Задание D</h2><br>';
    for ($k = 1; $k < 12; $k++){
        $fileNumber = $k;
        if ($k <= 9) {
            $fileNumber = '0' . $k;
        }
        //Формат имен файлов
        $ansName = "0" . $fileNumber . ".ans";
        $datName = "0" . $fileNumber . ".dat";

        //Загрузка из файла
        $fileContent = file_get_contents("D\\" . $datName, FILE_IGNORE_NEW_LINES);
        //Удаление комментариев
        while (str_contains($fileContent, "/*") && str_contains($fileContent, "*/")) {
            $begin = strpos($fileContent, "/*");
            $end = strpos($fileContent, "*/");
            $fileContent = substr_replace($fileContent, "", $begin, $end - $begin + 2);
        }
        $fileResult = "";
        //Выделение стилей через регулярное выражение
        $regex = '/((,|\s|[a-z0-9])+|#(.)*|\.([^,>{])*((,|>)*([^{])*))\{([^{}])*\}/';
        //Сохранение стилей в массив
        preg_match_all($regex, $fileContent, $matches);
        foreach ($matches[0] as $match) {
            //Разбиение стиля на заголовок и содержание
            $header = explode('{', $match)[0];
            $header = str_replace(", ", ",", $header);
            $header = trim ($header, " ");
            $header = str_replace("\n", "", $header);

            $content = explode('{', $match)[1];
            $content = str_replace("\n", "", $content);
            $content = str_replace(" ", "", $content);

            //Игнорирование пустых стилей
            if ($content == "}") {
                continue;
            }
            //Если у стиля есть все размеры для марджина
            if (str_contains($content, "margin-top") && str_contains($content, "margin-bottom")
                && str_contains($content, "margin-right") && str_contains($content, "margin-left")) {
                $topPos = strpos($content, "margin-top:");
                //Числовые значения для каждого размера
                $top = substr($content, $topPos + 11, 10);
                $top = explode('px;', $top)[0];
                $bottomPos = strpos($content, "margin-bottom:");
                $bottom = substr($content, $bottomPos + 14, 10);
                $bottom = explode('px;', $bottom)[0];
                $leftPos = strpos($content, "margin-left:");
                $left = substr($content, $leftPos + 12, 10);
                $left = explode('px;', $left)[0];
                $rightPos = strpos($content, "margin-right:");
                $right = substr($content, $rightPos + 13, 7);
                $right = explode('px;', $right)[0];

                $replacement = "";
                //При одинаковых размерах
                if ($top == $right && $top == $left && $top == $bottom) {
                    $replacement = "margin:" . $top . "px;";
                }
                else if ($left == $right) {
                    $replacement = "margin:" . $top . "px " . $left . "px " . $bottom . "px;";
                }
                else {
                    $replacement = "margin:" . $top . "px " . $right . "px " . $bottom . "px " . $left . "px;";
                }
                //Запись сокращенных размеров
                $content = preg_replace('/(margin-(top|left|bottom|right)([^;])*;){4}/', $replacement, $content);
            }
            //Если у стиля есть все размеры для паддинга
            if (str_contains($content, "padding-top") && str_contains($content, "padding-bottom")
                && str_contains($content, "padding-right") && str_contains($content, "padding-left")) {
                $topPos = strpos($content, "padding-top:");
                //Числовые значения для каждого размера
                $top = substr($content, $topPos + 12, 10);
                $top = explode('px;', $top)[0];
                $bottomPos = strpos($content, "padding-bottom:");
                $bottom = substr($content, $bottomPos + 15, 10);
                $bottom = explode('px;', $bottom)[0];
                $leftPos = strpos($content, "padding-left:");
                $left = substr($content, $leftPos + 13, 10);
                $left = explode('px;', $left)[0];
                $rightPos = strpos($content, "padding-right:");
                $right = substr($content, $rightPos + 14, 7);
                $right = explode('px;', $right)[0];

                $replacement = "";
                //При одинаковых размерах сокращаем
                if ($top == $right && $top == $left && $top == $bottom) {
                    $replacement = "padding:" . $top . "px;";
                }
                else if ($left == $right) {
                    $replacement = "padding:" . $top . "px " . $left . "px " . $bottom . "px;";
                }
                else {
                    $replacement = "padding:" . $top . "px " . $right . "px " . $bottom . "px " . $left . "px;";
                }

                $content = preg_replace('/(padding-(top|left|bottom|right)([^;])*;){4}/', $replacement, $content);
            }
            //Удаление лишних ;
            while (str_contains($content, ";;")) {
                $content = str_replace(";;", ";", $content);
            }
            //Удаление нулевых px
            $content = str_replace(" 0px", " 0", $content);
            $content = str_replace(":0px", ":0", $content);

            //Замена цвета с одинаковыми значениями
            //Пример - #FF2200 ---> #F20
            if (preg_replace('/#(.)\1\1\1(.)\2/', '#\\1\\1\\2', $content) != null) {
                $content = preg_replace('/#(.)\1\1\1(.)\2/', '#\\1\\1\\2', $content);
            }
            if (preg_replace('/#(.)\1(.)\2\2\2/', '#\\1\\2\\2', $content) != null) {
                $content = preg_replace('/#(.)\1(.)\2\2\2/', '#\\1\\2\\2', $content);
            }
            if (preg_replace('/#(.)\1(.)\2\1\1/', '#\\1\\2\\1', $content) != null) {
                $content = preg_replace('/#(.)\1(.)\2\1\1/', '#\\1\\2\\1', $content);
            }
            if (preg_replace('/#(.)\1(.)\2(.)\3/', '#\\1\\2\\3', $content) != null) {
                $content = preg_replace('/#(.)\1(.)\2(.)\3/', '#\\1\\2\\3', $content);
            }

            //Замена некоторых цветов на именования
            $content = str_replace("#CD853F", "peru", $content);
            $content = str_replace("#FFC0CB", "pink", $content);
            $content = str_replace("#DDA0DD", "plum", $content);
            $content = str_replace("#F00", "red", $content);
            $content = str_replace("#FFFAFA", "snow", $content);
            $content = str_replace("#D2B48C", "tan", $content);

            //Добавление пробелов для бордера
            $content = preg_replace('/(px)([a-zA-Z0-9#])/', '\\1 \\2', $content);
            $content = preg_replace('/(solid)([a-zA-Z0-9#])/', '\\1 \\2', $content);

            //Снова убираем px, если размер равен 0
            $content = str_replace(" 0px", " 0", $content);
            $content = str_replace(":0px", ":0", $content);

            //Формируем отредактированный стиль
            $content = trim($content, "}");
            $content = trim($content, ";");
            $fileResult .= $header . "{" . $content . "}";

        }
        //Преобразование входных данных в Html сущности
        $ans =  htmlentities(file_get_contents("D\\" . $ansName));
        $dat =  htmlentities(file_get_contents("D\\" . $datName));
        //Замена переходов строки
        $ans = str_replace("\n", "<br>", $ans);
        $dat = str_replace("\n", "<br>", $dat);
        $ans = trim($ans, "<br>");
        echo "$k TEST-----------------------------------------------------------------------------------NEXT<br>";
        echo "Входные данные:<br> $dat<br><br><br>Ответ:<br> $ans<br><br>Ответ программы:<br> $fileResult<br>";
    }