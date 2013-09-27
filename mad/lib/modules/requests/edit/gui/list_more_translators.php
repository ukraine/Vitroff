<?php
/**
 * список назначенных переводчиков (столбец справа в requests/edit/xxx
 */

function list_more_translators()
{
    global $rq_trans, $request_id;

    $templater   = new Templater();
    $translators = $rq_trans->getTranslators($request_id, true);
    $tr_html     = "";

    foreach ($rq_trans->getAssigned($request_id, true, true) as $k => $one) {
        $translators_list = array();

        /* список переводчиков кроме уже назначенных */
        foreach ($translators as $one_t) {
            $selected = "";
            if ($one_t->id == $one->translator_id) {
                $selected = " selected";
            }
            $translators_list[] = '<option value="' . $one_t->id . '"' . $selected . '>' . $one_t->lastname . " " . $one_t->firstname . '</option>';
        }

        $tr_block                          = new Templater();
        $tr_block->tpl["translator_id"]    = $one->translator_id;
        $tr_block->tpl["translators_list"] = implode($translators_list);
        $tr_block->tpl["input_price"]      = stdoutToString('GenerateInputTag', 'ppwt_' . $k, "Translator's price", "text", " &nbsp; ", "<br>
        ", "onkeyup='CalculatePrice(this.value,\"ppwt_" . $k . "\")'");

        $tr_block->tpl["input_deadline"] = stdoutToString('GenerateInputTag', "deadline_translator_" . $k, "Deadline for translator");
        $tr_block->tpl["calendar_js"]    = '<script type="text/javascript">calendar.set("labeldeadline_translator_' . $k . '");</script>';
        $tr_block->tpl["checkbox_pay"]   = stdoutToString('GenerateCheckBoxV2', array("translator_paid_" . $k => "Check here if the money were sent to worker"), "", "", "style='width:auto;'");

        $tr_html .= $tr_block->parsetemplate(getTemplateFullPath(__FILE__, '_translator_block'));

        unset($translators_list);
        unset($tr_block);
    }


    $templater->tpl["translators"] = $tr_html;
    $templater->render(getTemplateFullPath(__FILE__));
}