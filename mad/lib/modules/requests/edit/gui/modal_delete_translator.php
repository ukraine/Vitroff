<?php
/**
 * модальный диалог добавления переводчика
 */

function modal_delete_translator()
{
    global $request_id;

    $templater = new Templater();

    $templater->tpl["request_id"] = $request_id;

    $templater->render(getTemplateFullPath(__FILE__));
}
