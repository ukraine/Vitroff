<?php

/**
 * CRUD для работы с несколькими переводчиками на проектах
 *
 * PHP version 5
 *
 * @category Website
 * @package  Application
 * @author   Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://xxx
 */

/**
 *
 *
 * PHP version 5
 *
 * @category Website
 * @package  Application
 * @author   Vladimir Chmil <vladimir.chmil@gmail.com>
 * @license  http://mit-license.org/ MIT license
 * @link     http://xxx
 */
class RequestTranslator
{
    protected static $translators;

    /*
     * возвращает всех переводчиков
     */
    public function getTranslators($request_id = 0, $showactive = false)
    {
        if (is_null(self::$translators)) {
            $this->loadTranslators();
        }

        if (! empty($request_id)) {
            if ($showactive === false) {

                $exclusions = $this->getAssigned($request_id);

                if (! empty($exclusions)) {
                    return
                        ORM::for_table("translators")
                        ->select("lastname")
                        ->select("firstname")
                        ->select("id")
                        ->where_not_in("id", $exclusions)
                        ->order_by_asc("lastname")
                        ->find_many();
                }
            } else {
                return
                    ORM::for_table("translators")
                    ->select("lastname")
                    ->select("firstname")
                    ->select("id")
                    ->order_by_asc("lastname")
                    ->find_many();

            }
        }

        return self::$translators;
    }


    protected function loadTranslators()
    {
        if (is_null(self::$translators)) {
            self::$translators = ORM::for_table("translators")
                                 ->select("lastname")
                                 ->select("firstname")
                                 ->select("id")
                                 ->order_by_asc("lastname")
                                 ->find_many();
        }
    }

    /*
     * возвращает массив id всех переводчиков, работающих над проектом
     */
    public function getAssigned($request_id, $onlyAdditional = false, $asOrm = false)
    {
        $excludes = array();

        /* из табл. requests */
        if ($onlyAdditional === false) {
            $single = ORM::for_table("requests")
                      ->select("translator_id")
                      ->select("proofreader_id")
                      ->find_one($request_id);

            if (! empty($single->translator_id)) {
                $excludes[] = $single->translator_id;
            }

            if (! empty($single->proofreader_id)) {
                $excludes[] = $single->proofreader_id;
            }
        }

        /* из табл. TranslatorsToRequests */

        if ($asOrm === false) {
            $multi = ORM::for_table("TranslatorsToRequests")
                     ->select("translator_id")
                     ->where("request_id", $request_id)
                     ->find_many();

            if (! empty($multi)) {
                foreach ($multi as $one) {
                    $excludes[] = $one->translator_id;
                }
            }
        } else {
            return ORM::for_table("TranslatorsToRequests")
                   ->where("request_id", $request_id)
                   ->find_many();
        }

        return $excludes;
    }

    public function assign($request_id, $data = array())
    {
        $rec             = ORM::for_table("TranslatorsToRequests")->create();
        $rec->request_id = $request_id;

        foreach ($data as $k => $one) {
            $rec->$k = $one;
        }

        return $rec->save();
    }

    public function assignMany($raw_data)
    {
        if (! empty($raw_data["translator_additional_id"])) {
            $request_id = (int)filter_var($raw_data["id"], FILTER_SANITIZE_NUMBER_INT);

            $this->_clearTranslators($request_id);

            foreach ($raw_data["translator_additional_id"] as $key => $one) {
                $trans_data = array("translator_id" => filter_var($one, FILTER_SANITIZE_NUMBER_INT));

                if (isset($raw_data["ppwt_" . $key])) {
                    $trans_data["price"] = (float)filter_var($raw_data["ppwt_" . $key], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                }

                if (isset($raw_data["deadline_translator_" . $key])) {
                    $trans_data["deadline"] = filter_var($raw_data["deadline_translator_" . $key], FILTER_SANITIZE_STRING);
                }

                if (isset($raw_data["translator_paid_" . $key])) {
                    $trans_data["paid"] = (int)filter_var($raw_data["translator_paid_" . $key], FILTER_SANITIZE_NUMBER_INT);
                }

                $this->assign($request_id, $trans_data);

                unset($trans_data);
            }

            return true;
        } else {
            return false;
        }
    }

    private function _clearTranslators($request_id)
    {
        return ORM::for_table("TranslatorsToRequests")->where_equal("request_id", $request_id)->delete_many();
    }

    public function delTranslator($translator_id, $request_id)
    {
        $rec = ORM::for_table("TranslatorsToRequests")
               ->where_equal("translator_id", $translator_id)
               ->where_equal("request_id", $request_id)
               ->find_one();

        return $rec->delete();
    }
}