<?php
/**
 * добавление нового переводчика к проекту
 */

function request_add_new_translator()
{
    if (! empty($_POST["translator"])) {
        $tr = new RequestTranslator();
        $tr->assign(
            filter_input(INPUT_POST, "request_id", FILTER_SANITIZE_NUMBER_INT),
            array(
                "translator_id" => filter_input(INPUT_POST, "translator", FILTER_SANITIZE_NUMBER_INT),
                "price"         => filter_input(INPUT_POST, "ppwt_new", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                "deadline"      => filter_input(INPUT_POST, "deadline_translator_new", FILTER_SANITIZE_STRING),
                "paid"          => filter_input(INPUT_POST, "translator_paid_new", FILTER_SANITIZE_NUMBER_INT),
            )
        );
    }
}