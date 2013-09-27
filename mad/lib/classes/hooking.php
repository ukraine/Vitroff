<?php

/**
 * работа с хуками. Сами функции в lib/modules
 *
 * PHP version 5
 *
 * @category Website
 * @package  Application
 * @author   technofreak
 * @author   Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://thetechnofreak.com/technofreak/hook-system-php/
 */

/**
 * Хуки. Работают по похожему принципу как в wordpress
 *
 * подгружает все php файлы из HOOKS_PATH. Проходит по директориям рекурсивно.
 * Структура директорый в HOOKS_PATH такая:
 *
 * - HOOKS_PATH
 *    |- requests
 *        |- edit
 *            |- gui <- здесь формы с html и пр.
 *            |- actions <- здесь операции (crud etc)
 *        |- add
 *            |- ........
 *    |- translators
 *        |- add
 *            |- ........
 *
 * Это вложенность SEF ссылок: vitroff.com/mad/requests/edit/480
 *
 * PHP version 5
 *
 * @category Website
 * @package  Application
 * @author   Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://xxx
 */
class Hooking
{
    private $hooks;

    function __construct()
    {
        /* подгрузка модулей */
        foreach (
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    HOOKS_PATH
                )
            ) as $x
        ) {
            if (stripos($x->getPathname(), ".php") !== false && is_dir($x->getPathname()) === false) {
                require_once $x->getPathname();
            }
        }

        $this->hooks = array();
    }

    function add_action($where, $callback, $priority = 50)
    {
        if (! isset($this->hooks[$where])) {
            $this->hooks[$where] = array();
        }
        $this->hooks[$where][$callback] = $priority;
    }

    function remove_action($where, $callback)
    {
        if (isset($this->hooks[$where][$callback]))
            unset($this->hooks[$where][$callback]);
    }

    function execute($where, $args = array())
    {
        if (isset($this->hooks[$where]) && is_array($this->hooks[$where])) {
            arsort($this->hooks[$where]);
            foreach ($this->hooks[$where] as $callback => $priority) {
                call_user_func_array($callback, $args);
            }
        }
    }
}

/* функц. обертки для Hooking (по типу wordpress) */
function add_action($where, $callback, $priority = 50)
{
    global $hooking_daemon;
    if (isset($hooking_daemon) && is_callable($callback))
        $hooking_daemon->add_action($where, $callback, $priority);
}

function remove_action($where, $callback)
{
    global $hooking_daemon;
    if (isset($hooking_daemon))
        $hooking_daemon->remove_action($where, $callback);
}

function execute_action($where, $args = array())
{
    global $hooking_daemon;
    if (isset($hooking_daemon))
        $hooking_daemon->execute($where, $args);
}

