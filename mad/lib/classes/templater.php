<?php

/*
 * шаблонизтор
 *
 * *** взят из проекта проверки
 *     локализаций ***
 *
 * PHP version 5
 *
 * @category Linkchecker
 * @package  Auxilary
 * @author   coma
 * @author   ???, Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://---
 */

/**
 * templater
 *
 * PHP version 5
 *
 * @category Linkchecker
 * @package  Auxilary
 * @author   ???, Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://---
 */
class Templater
{

    public $tpl = array();
    public $tpl_file = '';
    public $baze_tpl_file = 'index';
    public $time_adm_mem = TRUE;
    public $current_tpl = '';
    protected $time_start;
    protected $Lang = null;

    public function __construct($arr = array(), $lang = null)
    {
        ob_start();
        if (COUNT($arr) > 0) {
            $this->inset($arr);
        }
        $this->current_tpl = '';
        if ($lang) {
            $this->Lang = $lang;
        }
    }

    public function inset($arr = array())
    {
        if (COUNT($arr) > 0) {
            foreach ($arr AS $n => $io) {
                $this->tpl[$n] = $io;
            }
        }
    }

    public function parsetemplate($tppl = '', $array = null)
    {
        if (!$array) {
            $array = $this->tpl;
        }
        $tppl = $this->gettemplate($tppl);
//        $lang = Config::get("lang");

        preg_match_all("/\[lang\]([a-zA-Z0-9_\-\s]*?)\[\/lang\]/is", $tppl, $s);

        if (sizeof($s[0]) > 0) {
            foreach ($s[0] AS $k => $v) {
                //$tppl = str_ireplace($s[0][$k], $this->Lang->get($s[1][$k]), $tppl);
                $tppl = str_ireplace($s[0][$k], $lang->$s[1][$k], $tppl);
            }
        }
        unset($s);
        return preg_replace('#\{([a-z0-9\-_]*?)\}#Ssie',
            '( ( isset($array[\'\1\']) ) ? $array[\'\1\'] : \'\' );', $tppl);
    }

    public function gettemplate($tppl = '')
    {
        return (strpos($tppl, '.') === false)
            ? file_get_contents($this->current_tpl . (($tppl != '') ? $tppl : $this->tpl_file) . TPLEXT)
            : file_get_contents($tppl);
    }

    public function render($tppl = '', $array = null)
    {
        echo $this->parsetemplate($tppl, $array);
        ob_flush();
        flush();
    }

    public function display($tppl = '')
    {
        $this->tpl['domen'] = BASE_URL;
        $this->tpl['css_path'] = BASE_URL . 'tpl/css/';
        $this->tpl['js_path'] = BASE_URL . 'tpl/js/';
        echo $this->parsetemplate((($tppl != '') ? $tppl : $this->baze_tpl_file));
        ob_flush();
        flush();
        ob_clean();
        exit();
    }
}
