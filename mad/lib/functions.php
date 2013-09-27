<?php
/**
 * хелперы
 *
 * @author Vladimir Chmil <vladimir.chmil@gmail.com>
 */

/**
 * stdoutToString()
 *
 * вызов функций mad с выводом результата из выполнения в переменную (вместо echo)
 *
 * Пример вызова:
 * $echo = stdoutToString('GenerateInputTag', 'ppwt1', "Translator's price", "text", " &nbsp;", "<br>", "");
 *
 * В $echo будет вывод GenerateInputTag()
 *
 * @param имя вызываемой функции
 * @param переменная ф-ции 1
 * .........................
 * @param переменная ф-ции N-1
 * @param переменная ф-ции N
 *
 * @return string
 */
function stdoutToString()
{
    $args = func_get_args();
    $func = array_shift($args);

    if (is_callable($func)) {
        ob_start();

        call_user_func_array($func, $args);

        $tag = ob_get_contents();
        ob_end_clean();

        return $tag;
    }

    return "";
}

/**
 * возвращает полный путь к шаблону html. Имя шаблона такое-же как и $file
 *
 * @param $file   имя php скрипта (__FILE__)
 * @param $suffix file name suffix
 *
 * @return string
 */
function getTemplateFullPath($file, $suffix = "")
{
    return dirname($file) . "/" . str_replace(".php", "", basename($file)) . $suffix . ".html";
}


/**
 * возвращает массив с ключами, которые есть в $allowed_fields
 *
 * @param $input_array
 * @param $allowed_fields
 *
 * @return array
 */
function filterRequestArray($input_array, $allowed_fields)
{
    return array_intersect_key($input_array, array_flip($allowed_fields));
}
