<?php
error_reporting(0);

// подгрузка общих для админки и витрины функций
include "../lib/shared.php";

// общие функции для работы с mysql (insert, update, delete)
include "../lib/default.func.php";

// Инициализация админки
include "lib/lib.php";

/* новый функционал 2013 */
include "lib/init.php";

// Обработчик событий, переданных через GET/POST
include "lib/default.php";

// подключение шаблона внешнего вида (есть два шаблона)
include "tpl/$pagetemplate.html";

?>