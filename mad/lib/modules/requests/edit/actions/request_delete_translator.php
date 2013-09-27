<?php
/**
 * удаление переводчика из проекта
 */

function request_delete_translator()
{
    if (! empty($_POST["translator_del_id"])) {
        $tr = new RequestTranslator();
        $tr->delTranslator(
            filter_input(INPUT_POST, "translator_del_id", FILTER_SANITIZE_NUMBER_INT),
            filter_input(INPUT_POST, "request_id", FILTER_SANITIZE_NUMBER_INT)
        );
    }
}