<?php
/**
 * добавление доп. данных в global $f (редактирование заказа)
 */

function update_request_f()
{
    global $f;

    $tr                 = new RequestTranslator();
    $active_translators = $tr->getAssigned($f["id"], true, true);

    foreach ($active_translators as $key => $one) {
        $f["ppwt_" . $key]                = $one->price;
        $f["deadline_translator_" . $key] = $one->deadline;
        $f["translator_paid_" . $key]     = $one->paid;
    }
}