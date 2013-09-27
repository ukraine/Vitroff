<?php
/**
 * сохранение изменний в доп. переводчиках
 */
function request_after_update()
{
    $tr = new RequestTranslator();
    $tr->assignMany($_POST);
}