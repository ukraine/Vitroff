<?
// echo $section.$action;
switch ($action) {

    default:

        $action = "default";
        $title  = ucfirst($section) . " &#151; viewing the list";

        // Инициализация перменных
        $where = $qeuryforpaginator = $paging = "";

        // Получение дополнительных параметров
        parse_str($_SERVER['QUERY_STRING'], $query);
        foreach ($query as $key => $val) {
            if ($key !== "section" & $key !== "page" & $key !== "sortby" & $key !== "ascdesc" && $key !== "param") {

                // Дополнительная строка для запроса в БД
                $where = " WHERE $key = '$val'";

            }

            if ($key !== "section" & $key !== "page") {

                // Для пагинатора
                $qeuryforpaginator .= "$key=$val&";

            }

        }

        $url = $_SERVER['REDIRECT_URL'] . "?" . $qeuryforpaginator;

        // Формирование сути нашего запроса
        $sql = "FROM `$section` $where ORDER BY `$orderby` $ascdesc";

        // echo $sql;

        // Считаем общее кол-во объектов
        $res = @mysql_fetch_array(mysql_query("select count(*) as count $sql"));

        // Если хоть что-то есть, дальше выводим список
        if ($res) {

            // Всего записей
            $count = $res['count'];

            // Считаем общее кол-во страниц
            $totalpages = ceil($count / $Settings['itemsonpage']);

            $page = 1;

            // узнаем на какой странице находимся
            if (! empty($_GET['page'])) $page = $_GET['page'];

            // echo "page: $page<BR>";

            // Выставление лимита кол-ва объектов на странице (так же исп-зуется для нумерации объ. на страницах)
            $startlimit = ($Settings['itemsonpage'] * $page) - $Settings['itemsonpage'];

            // $startlimitv2 = $startlimit-$Settings['itemsonpage'];

            // echo "LIMIT $startlimit, XX<BR>";

            // Первый объект на странице
            $startobject = $startlimit + 1;

            // Последний объект на странице
            $endobject = $startobject + $Settings['itemsonpage'] - 1;

            // Если объектов меньше, чем разрешено на странице
            if ($count <= $Settings['itemsonpage']) $endobject = $count;

            // Исп. для последней страницы
            if ($endobject > $count) $endobject = $count;

            // Формирование конечного запроса
            $sql = $sql . " LIMIT $startlimit, $Settings[itemsonpage]";

            // echo $sql;

            // Собственно делаем выборку
            $res = mysql_query("SELECT * $sql");


        }

        break;

// Страничка добавления элемента
    case "add":

        $action                  = "addedit";
        $title                   = ucfirst($section) . " &#151; adding new";
        $displayitwithsourcetext = "none";

        break;

// Выполнение добавления
    case "do_add":

        $title = "DEMONSTRATION VERSION LIMITATIONS";

            $action                  = "addedit";
            $title                   = ucfirst($section) . " &#151; adding new";
            $displayitwithsourcetext = "none";

            // Проверяем получаемые данные на корректность и если все хорошо, вводим данные
            if (IsRequiredFieldsFilled($SectionsRequiredFields[$section])) {

                // REQUESTS: Если есть поле с исх. текстом то подсчитываем слова
                if (! empty($_POST['source_text'])) $_POST['wordcount'] = str_word_count($_POST['source_text']);

                // Упрощаем работу с именами
                /* if ($section == "customers" && !empty($_POST['firstnamelastname'])) {
                    $name = explode($_POST['firstnamelastname']," ");
                    $_POST['lastname'] = $name[1];
                    $_POST['firstname'] = $name[0];
                    } */

                // ALL: Временная метка поступления
                $_POST['registrationtime'] = date("Y-m-d H:m:s");

                // Вставляем данные в базу данных
                if (insert_data($_POST, $section)) {

                    // Если присутствуют файлы, добавляем также и их
                    if ($_FILES) insert_files(ProcessSQL('requests', "ORDER BY `id` DESC LIMIT 0,1"));

                    // Если нажата кнопка "Continue Edit", то узнаем ID только что созданной страницы и продолжаем редактирование
                    if (! empty($_POST['submit'])) {
                        $action = "addedit";
                        header("Location: {$siteurl}$section/edit/" . ProcessSQL('requests', "ORDER BY `id` DESC LIMIT 0,1"));
                        break;
                    }

                    // Если открыто всплывающее окно
                    if ($pagetemplate == "popup") {
                        $section   = "default";
                        $action    = "popupclose";
                        $error_msg = "Operation completed";
                    } // В противном случае выводим
                    else header("Location: {$siteurl}$section/");

                } // Если не смогли внести данные по каким-либо причинам - выводим сообщение об ошибке
                else {
                    $title     = "Error";
                    $error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

                    var_dump(mysql_error());
                }

            }

        break;

// Страничка редактирования элемента
    case "edit":

        $action                  = "addedit";
        $title                   = ucfirst($section) . " &#151; editing existing";
        $displayitwithsourcetext = "none";

        // Делаем выборку с данными о конкретной объекте
        $f = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");
        execute_action("update_edit_request_data");

        if (! empty($f['source_text'])) $displayitwithsourcetext = "inline";

        break;

// Выполнение редактирования
    case "do_edit":

        // DEMO VERSION LIMITATIONS
        /*

        $title = "DEMONSTRATION VERSION LIMITATIONS";

        if ($section == "status" || $section == "templates") {

            $error_msg = "Sorry. This feature is disabled in the demo-version";
            $section = "default";
            $action = "demo";

        }

            else { */

        // DEMO VERSION LIMITATIONS

        // 06.03.2007 Added "Save & Continue Func-on"

        $action = "edit";
        $title  = ucfirst($section) . " &#151; editing existing";

        $location = $siteurl . $section . "/";

        // Если загружались какие-либо файлы, записываем их в определенную директорию
        // а информацию о них в базу данных

        if ($_FILES) insert_files($_GET['id']);

        // Если в поле "исходный текст" есть текст, то считаем кол-во слов, занося их в переменную
        if (! empty($_POST['source_text'])) $_POST['wordcount'] = str_word_count($_POST['source_text']);


        // 08.11.2009 Если была указана сумма в поле "оплачено" раздела запросы при редактировании то меняем статус на оплачено
        if ($section == "requests" && $_POST['amountpaid'] > 0 && $_POST['status_id'] <= 2) $_POST['status_id'] = "3";

        // "Смена пароля": Если мы на странице изменения пароля, то шифруем его и заносим в таблицу
        if (! empty($_POST['password']) && $_POST['password'] !== $UserDetails['password']) {
            $_POST['password'] = md5($_POST['password']);
        } else    unset($_POST['password']);

        // Кнопка "Continue Edit" - продолжение редактирования после сохранения
        if (! empty($_POST['submit'])) $location = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        // Сохраняем данные и возвращаемся на главную страницу раздела
        $filter_fields = array(
            'status_id', 'area_id', 'deadline', 'customer_id', 'customer_project_id', 'instructions',
            'isprojectactive', 'iscertificationrequired', 'isnotarizationrequired', 'isscanrequired',
            'isexpressmailrequired', 'istranslationmemory', 'postal_tracking_number', 'comments',
            'wordcount', 'characters', 'ppw', 'postpayment', 'estimatedprice', 'discount', 'amountpaid',
            'transaction_id', 'translator_id', 'ppwt', 'deadline_translator', 'translator_paid', 'proofreader_id',
            'ppwp', 'deadline_proofreader', 'proofreader_paid',  'source_text', 'id', 'action'
        );
        $arr           = filterRequestArray($_POST, $filter_fields);

        if (edit_data($arr, $section)) {
            execute_action("request_after_update");
            header("Location: $location");
        } else {
            $title     = "Error";
            $error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

            var_dump(mysql_error());

        }

        /*	} 	// DEMO VERSION LIMITATIONS */

        break;

    case "addnew":
        execute_action("assign_new_translator");

        header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        break;

    case "delete_translator":
        execute_action("delete_translator");

        header("Location: http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        break;

// Просмотр элемента
    case "duplicate":

        $_POST = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");

        unset($_POST['id'], $_POST['amount_paid'], $_POST['transaction_id'], $_POST['amountpaid']);

        $_POST['isprojectactive'] = '1';
        $_POST['status_id']       = '2';

        $_POST['deadline'] = date("Y-m-d", time() + (86400 * 5));

        // Сохраняем данные и возвращаемся на главную страницу раздела
        if (insert_data($_POST, $section)) {
            $location = $siteurl . $section . "/edit/" . mysql_insert_id();
            header("Location: $location");
        } else {
            $title     = "Error";
            $error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

        }

        break;

// Просмотр элемента
    case "closeproject":

        $action = "closeproject";
        $title  = "Closing the project";

        mysql_query("UPDATE `requests` SET `amountpaid` = `estimatedprice`, `status_id` = '10', `isprojectactive` = '0' WHERE `id` = $_GET[id]");

        header("Location: $siteurl{$section}/view/$_GET[id]");

        break;

// Просмотр элемента
    case "view":

        $action = "view";
        $title  = "View";

        $sendemailvisibility = "none";

        // Получаем массив с данными об объекте
        $f = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");

        // Если есть информация по объекту с таким ИД, то начинаем рабоать
        if ($f) {

            // Массив с данными о шаблоне согласно текущему статусу
            $template = @RunQueryReturnDataArray("templates", "WHERE status_id=$f[status_id]");

            // Откуда берем данные о цене для вставки в шапку
            $priceperword = array("1" => "ppwt", "2" => "ppw");

            // Если существует шаблон для текущего статуса проекта
            if ($section == "requests" && $template) {

                $sendemailvisibility = "block";
                $baseppw             = $f[$priceperword[$template['group_id']]];

            }

        } else {
            $error_msg = "Object not found. Please <a href='/mad/'><B>return back</B></a> to make a new search";
            $section   = "default";
            $action    = "notfound";
        }

        break;

// Страничка написания письма
    case "compose":

        global $filelisting;

        // Откуда берем данные о цене для вставки в шапку
        $priceperword = array("1" => "ppwt", "2" => "ppw");

        $action = "compose";
        $title  = "Compose email";

        // Некоторые базовые конфиги
        $curr = "-USD";

        // Данные о конкретном переводе
        $f = @RunQueryReturnDataArray($section, "WHERE id=$_GET[id]");

        // Получаем массив с данными о шаблоне для текущего статуса перевода
        $template = @RunQueryReturnDataArray("templates", "WHERE status_id=$f[status_id]");

        $baseppw = $f[$priceperword[$template['group_id']]];

        // Получаем данные с именем и емейлом получателя
        $recepient_details = RunQueryReturnDataArray($group[$template['group_id']] . "s", $more = "WHERE id=" . $f[$group[$template['group_id']] . "_id"], $column = "*");

        $baseprice = round($f['wordcount'] * $baseppw, 2);

        if (! empty($f['currency'])) $curr = strtoupper("-" . $f['currency']);
        $baseprice = array($baseprice . $curr, round(($baseprice * 1.3), 2) . $curr, round(($baseprice * 1.699), 2) . $curr);

        // формируем данные об отправителе
        $f['fromname']  = "$UserDetails[firstname] $UserDetails[lastname]";
        $f['fromemail'] = $UserDetails['email'];

        // Данные о получателе
        $f['toname']  = "$recepient_details[firstname] $recepient_details[lastname]";
        $f['toemail'] = $recepient_details['email'];

        // Данные о заголовке письма (превью)
        $f['from'] = $f['fromname'] . " &lt;" . $f['fromemail'] . "&gt;";
        $f['to']   = $f['toname'] . " &lt;" . $f['toemail'] . "&gt;";

        break;

// Предварительный просмотр письма
    case "preview":

        $action = "preview";
        $title  = "Email Preview";

        // Данные о конкретном объекте(рассылке, переводчике)
        $f = @RunQueryReturnDataArray($section, "WHERE id=$_GET[id]");

        @$f['to'] = $_POST['toname'] . " &lt;" . $_POST['toemail'] . "&gt;";
        @$f['from'] = $_POST['fromname'] . " &lt;" . $_POST['fromemail'] . "&gt;";

        break;

    // Предварительный просмотр письма
    case "download":

        $action = "download";

        // Данные о конкретном объекте(проекте,переводчике и тд.)
        $f = @RunQueryReturnDataArray("customers c, requests r", "WHERE r.id='$_GET[id]' AND r.customer_id = c.id", "*");

        $filename = $_GET['id'] . "-reamde.txt";

        $f['iscertificationrequired'] = $YesOrNoArray[$f['iscertificationrequired']];
        $f['isnotarizationrequired']  = $YesOrNoArray[$f['isnotarizationrequired']];
        $f['isscanrequired']          = $YesOrNoArray[$f['isscanrequired']];
        $f['isexpressmailrequired']   = $ShippingOptions[$f['isexpressmailrequired']];

        header('Content-Description: File Transfer');
        header('Content-Type: Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        echo ApplyTemplate("shipping_label", $f);
        die();

        break;

// Предварительный просмотр письма
    case "dlinvoice":

        $microtime = time();

        // Данные о конкретном объекте(проекте,переводчике и тд.)
        $f = @RunQueryReturnDataArray("customers c, requests r", "WHERE r.id='$_GET[id]' AND r.customer_id = c.id", "*");

        $filename = $_GET['id'] . "-invoice.doc";

        $f['iscertificationrequired'] = $YesOrNoArray[$f['iscertificationrequired']];
        $f['isnotarizationrequired']  = $YesOrNoArray[$f['isnotarizationrequired']];
        $f['isscanrequired']          = $YesOrNoArray[$f['isscanrequired']];
        $f['isexpressmailrequired']   = $ShippingOptions[$f['isexpressmailrequired']];
        $f['wire_transfer_fee']       = $Settings['wire_transfer_fee'];
        $f['currency']                = $Settings['currency'];
        $f['service']                 = getNameById($f['area_id'], 'rates', 'name');
        $f['discount_amount']         = round($f['estimatedprice'] * ($f['discount'] / 100), 2);
        $f['online_amount']           = $f['estimatedprice'] - $f['discount_amount'];
        $f['invoicedate']             = date("F d, Y", $microtime);
        $f['duedate']                 = date("F d, Y", $microtime + 86400 * 10);
        $f['total_due']               = round($f['estimatedprice'] - $f['discount_amount'] - $f['amountpaid']);

        header('Content-Description: File Transfer');
        header('Content-Type: application/rtf');
        header('Content-Disposition: attachment; filename=' . basename($filename));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        echo ApplyTemplate("invoice", $f);
        die();

        break;

// Отправка письма
    case "do_send":

        $action              = "view";
        $sendemailvisibility = "none";

        $title = "Status of Request";
        if (sendmail2()) {

            $details = array(
                "baseppw"   => $_POST['baseppw'],
                "status_id" => $_POST['status_id'] + 1,
                "id"        => $_GET['id']
            );

            edit_data($details, $_GET['section']);
        }

        $title = "View";
        $f     = @RunQueryReturnDataArray($section, "WHERE id=$_GET[id]");

        if ($section == "requests" && $f['estimatedprice'] > 0 && $f['status_id'] == 3) $baseppw = $f['ppwt'];
        elseif ($section == "requests" && $f['estimatedprice'] > 0) $baseppw = round($f['estimatedprice'] / $f['wordcount'], 4); else $baseppw = $calculator['baseppw'];

        break;

// Отправка письма
    case "do_sendnewsletter":

        sendnewsletters();

        $action = "view";
        $f      = @RunQueryReturnDataArray($section, "WHERE id=$_GET[id]");
        $title  = "Status of Request";

        break;

// Страница закрытия всплывающего окна
    case "popupclose":

        $action = "popupclose";
        $title  = "Graphical View";

        break;

// Отображение статистики
    case "stats":

        $action = "stats";
        $title  = "Different stats";

        break;

// AJAX. Изменения значения 
    case "changevalue":

        $table   = $_GET['section'];
        $request = parse_url($_SERVER['REQUEST_URI']);
        parse_str($request['query'], $details);

        if (ChangeValue()) {
            echo "OK";
            exit;
        }

        break;

// Страница изменения пароля
    case "login":

        $action = "login";
        $title  = "Password change";

        $f = RunQueryReturnDataArray($section, "WHERE `id` = $_SESSION[staff_id]");

        break;

// Мой аккаунт
    case "mydetails":

        $action = "mydetails";
        $title  = "My details";

        $f = RunQueryReturnDataArray($section, "WHERE `id` = $_SESSION[staff_id]");

        break;

// Страница c банковской информацией
    case "bankdetails":

        $action = "bankdetails";
        $title  = "Bank details";

        $f = RunQueryReturnDataArray($section, "WHERE `id` = 1");

        break;

// Фактическое удаление объекта
    case "delete":

        // DEMO VERSION LIMITATIONS

        $title = "DEMONSTRATION VERSION LIMITATIONS";

        if ($section == "status" || $section == "templates") {

            $error_msg = "Sorry. This feature is disabled in the demo-version";
            $section   = "default";
            $action    = "demo";

        } else {

            if (delete_data("id", $section, $_GET['id'])) {

                delete_files($_GET['id']);
                header("Location: {$siteurl}$section/");

            } else {

                $title     = "Error";
                $error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

            }

        }

        break;

// Выполнение запроса к базе данных
    case "do_runquery":

        $action    = "default";
        $error_msg = $translation['137'];

        $title = "Developers corner";

        // Don't change here
        if (mysql_query($_POST['sql'])) return 1; else {
            $error_msg = $translation['138'] . mysql_errno() . ": " . mysql_error();

            return 0;
        }
        // Don't change here */


        break;

}
?>
