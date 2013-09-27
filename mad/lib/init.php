<?php
/**
 * инициализация mad
 *
 * @author Vladimir Chmil <vladimir.chmil@gmail.com>
 */

/* константы */
define("CLASS_PATH", dirname(__FILE__) . '/classes');
define("HOOKS_PATH", dirname(__FILE__) . '/modules');

/* functions */
include "functions.php";
include CLASS_PATH . "/hooking.php";

/* - хуки - */
$hooking_daemon = new Hooking();

/* global init */
add_action("on_init", "init_orm");
add_action("on_init", "init_autoload");

execute_action("on_init");

/* init html */
add_action("init_html_header", "init_javascript");
add_action("init_html_header", "init_css");

/* # Добавление нескольких переводчиков к проекту */
/* upd. global $f - загржуаем данные нескольких переводчиков */
add_action("update_edit_request_data", "update_request_f");

/* ## GUI */
add_action("after_staff_tab_modal", "modal_add_translator");
add_action("after_staff_tab_modal", "modal_delete_translator");
add_action("list_more_translators", "list_more_translators");
add_action("after_translator_in_project_list", "view_additional_people");

/* ## actions */
add_action("request_after_update", "request_after_update");
add_action("assign_new_translator", "request_add_new_translator");
add_action("delete_translator", "request_delete_translator");



