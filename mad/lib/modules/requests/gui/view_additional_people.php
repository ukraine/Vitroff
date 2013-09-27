<?php
/**
 * вывод дополнительно занятых в проекте людей в списке проектов
 */

function view_additional_people()
{
    global $f;

    $tr       = new RequestTranslator();
    $assigned = $tr->getAssigned($f['id'], true, false);

    if (! empty($assigned)) {
        $templater                      = new Templater();
        $templater->tpl["project_link"] = "/mad/requests/edit/" . $f['id'];
        $templater->tpl["count"]        = count($assigned);
        $templater->render(getTemplateFullPath(__FILE__));
    }
}
