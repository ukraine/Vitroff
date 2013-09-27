<?php
/**
 * модальный диалог добавления переводчика
 */

function modal_add_translator()
{
    global $rq_trans, $request_id;

    $templater = new Templater();

    /* список переводчиков */
    $translators_list = array();
    foreach ($rq_trans->getTranslators($request_id) as $one) {
        $translators_list[] = '<option value="' . $one->id . '">' . $one->lastname . " " . $one->firstname . '</option>';
    }

    $templater->tpl["request_id"] = $request_id;
    $templater->tpl["translators_list"] = implode($translators_list);

    $templater->tpl["input_price"] = stdoutToString('GenerateInputTag', 'ppwt_new', "Translator's price", "text", " &nbsp; ", "<br>
        ", "onkeyup='CalculatePrice(this.value,\"ppwt_new\")'");

    $templater->tpl["input_deadline"] = stdoutToString('GenerateInputTag', "deadline_translator_new", "Deadline for translator");
    $templater->tpl["checkbox_pay"]   = stdoutToString('GenerateCheckBoxV2', array("translator_paid_new" => "Check here if the money were sent to worker"), "", "", "style='width:auto;'");

    $templater->render(getTemplateFullPath(__FILE__));
}
