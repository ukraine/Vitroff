<?

error_reporting(E_ALL);

// Подключаемся к БД
include "../lib/configs.php";

// Инициализация
global $Settings, $UserDetails;

// Инициализация дополнительных переменных
$copyrights = "";
unset($_GET['param']);

// Обязательные к заполнению поля в зависимости от раздела
$SectionsRequiredFields = array(
	"requests"		=> array("deadline" => "Deadline"),
	"translators"	=> array("email" => "Email address", "lastname" => "Last name"),
	"customers"		=> array("email" => "Email address", "lastname" => "Last name"),
	"languages"		=> array("name" => "Name", "code" => "Code"),
	"newsletters"	=> array("subject" => "Subject of the email", "content" => "Content of the newsletter"),
	"paymenttypes"	=> array("name" => "Name"),
	"pairs"			=> array("source" => "Source language"),
	"rates"			=> array("name" => "Name", "ppw" => "Price per word"),
	"templates"		=> array("name" => "Name", "subject" => "Subject of the email template", "content" => "Content of the template"),
	"status"		=> array("name" => "Name of workflow stage", "description" => "Description of the stage")
);

// Отображать по n элементов с каждой стороны в пагинаторе
define("SHOWEACHSIDE",'5');

// Названия к кнопкам в зависимости от действия
$ButtonNames = array(
	"add" => "Add",
	"edit" => "Save"
);

// Группы файлов с текстами
$TranslationTypes = array(

	"1" => "source",
	"2" => "proofread",
	"3" => "translation"

);

// Используется для чекбоксов
$checkedOrNot = array("","checked");

// Группы людей. Требуется для формирования писем
$group = array("1"=>"translator", "2"=>"customer");

// Шаблон интерфейса по умолчанию
$pagetemplate = "index";

// Если указан другой тип шаблона интерфейса
if (!empty($_GET['pagetemplate'])) $pagetemplate = $_GET['pagetemplate'];

// Проверка на наличие переменных
if (empty($_REQUEST['section'])) $section = "default"; else $section = $_REQUEST['section'];
if (empty($_REQUEST['action'])) $action = "default"; else $action = $_REQUEST['action'];
if (empty($error_msg)) $error_msg = "";
if (!empty($_POST['action'])) $action = $_POST['action'];

// Сортировка по умолчанию для некоторых разделов
$order = array("requests"=>"deadline", "translators"=>"lastname");

// Получаем данные по какому полю сортировать
if (!empty($_GET['sortby'])) $orderby = $_GET['sortby']; else { 
	
	$orderby = @$order[$section];
	if (!$orderby) $orderby="id";
	
}

// Получаем данные о последовательности сортировки
if (!empty($_GET['ascdesc'])) $ascdesc = $_GET['ascdesc']; else $ascdesc="desc";

$status = "";
if (!empty($_GET['status_id'])) $status = $_GET['status_id'];

// AJAX. Функция для изменения значения поля конкретного объекта.
function ChangeValue() {

	global $details, $table;

	$details["id"] = $_REQUEST['id'];
	edit_data (&$details, &$table);

	return 1;

}

// Если отсутствует переменная - отображаем слово "Недоступно"
function notAvailable($variable) {

	if (empty($variable)) return "no";
	else return $variable;

}

function getPaymentMethodByPersonId($table, $id) {

	$array = ProcessSQL ($table, "WHERE id='$id'", $column="`payment_prefs_id` AS id");
	return ProcessSQL ("paymenttypes", "WHERE id='$array[id]'", $column="`name`");

}


// Получить значение общего кол-ва объектов в таблице при определенном условии
function GetTotalData ($table, $more="") {

	$res = RunQueryReturnDataArray($table, $more, $column="COUNT(*)");
	return $res['COUNT(*)'];
}

// Обрезаем видимую часть строки, если она больше $limitto символов
function limitVisiblePart($fieldname, $limitto="16", $threedots = "") {

	global $f;

	if (strlen($fieldname) > $limitto) $threedots = "...";
	$fieldname = strip_tags(substr(stripslashes($fieldname), 0, $limitto)).$threedots;
	return $fieldname;

}

function checkbox($field)	{

		global $f;
		if($f[$field] == "y") echo " checked";
		else echo "shit";

}

function radiobox($field, $number)	{

	global $f;

	if($f[$field] == $number) echo " checked";

}

// Отображение строки дедлайна другим цветом
function DeadLineIsCurrentDate($date) {

	global $f;
	if ($date <= date('Y-m-d H:i:s') && $f['status_id']!=10 && $f['status_id']!=2 && $f['status_id']!=11) return "id='today'";

}

// Вывод списка файлов, связанных с заказом.

// $translationtype_id означает тип документа: 1-оригинал, 2-перевод
// $listfiles = вывести просто список файлов без ссылок на них
// $translation = слово, вставляемое в название переводенного файла
// 17.10.2007
function getRequestFiles($request_id, $translationtype_id="1", $listfiles="0",$translation="", $unsetattachfiles="") {

	global $f, $filelisting, $TranslationTypes;

	// echo "RUN";

	// Если запрос существует
	if ($f) {

		$data = mysql_query("
			SELECT * FROM `files` 
			WHERE `request_id` = $request_id 
			AND `translationtype_id` = $translationtype_id
			ORDER BY id ASC
		");
	
		// echo mysql_num_rows($data);

		// Получение массива с данными о файлах, прикрепленных к заказу
		for ($i=0; $i < mysql_num_rows($data); $i++)
			
			{

				// Получаем массив с данными о прикрепленных файлах
				$files = mysql_fetch_array($data);

				// Вывод ссылки "Удалить" на странице редактирования заказа
				$delete = array(
					"edit" => " &nbsp; &#151; &nbsp; <span onclick=\"unlinkfile('$files[name]','$files[id]','fileid$files[id]')\" class=\"redlink\">delete</span>"
				);

				// Получение массива с именами файлов для прикрепления к файлу
				if ($unsetattachfiles > "0") $filelisting .= $files['name'].";";

				// Вывод обычныго списка файлов, прикрепленных к этому заказу...
				if ($listfiles !="0") echo "<div>" . str_replace($TranslationTypes[$translationtype_id], $translation, $files['name']) . "</div>";

				// ...вместо ссылок на него, как здесь
				else echo 
				"\n<div id='fileid$files[id]'><a href='/getfile.php?file=$files[name]'>$files[name]</a>" . @$delete[$_GET['action']] . "</div>";

			}	// Конец for(...$data)

	}	// Конец if ($f)

				if ($translationtype_id == "3") unset($filelisting);


}

// Добавление файлов
function delete_files($request_id) {

	global $filestoragepath;

	$data = mysql_query("
			SELECT name FROM `files` 
			WHERE `request_id` = $request_id 
	");

	// Получение массива с данными о файлах, прикрепленных к заказу
	for ($i=0; $i < mysql_num_rows($data); $i++) {
	
		// Получаем массив с данными о прикрепленных файлах
		$files = mysql_fetch_array($data);

		// Удаляем файл физически с сервера
		unlink($filestoragepath.$files['name']);
	
	}

	// А теперь удаление всех файлов, прикрепленых к заказу одним махом
	delete_data("request_id", "files", $_GET['id']);

}


function SaveSourceIntofile($id) {

	global $filestoragepath;
	
		// Получаем контент
		$content = RunQueryReturnDataArray ("requests", "WHERE `id`=$id", "source_text");

		// Название нашего файлика
		$details['name'] = $id . "-source.doc";

		// Открываем файлик для записи.
		$fh=fopen($filestoragepath.$details['name'],"w");

		// Записываем контент
		fwrite($fh,$content['source_text']); 

		// Закрываем файлик
		fclose($fh);

		$details['translationtype_id'] = "1";
		$details['request_id'] = $id;
		
		// Вставляем данные о файле в таблицу файлов
		insert_data($details, "files");

		$request['source_text'] = "";
		$request['id'] = $id;

		// Удаляем исходный текст из таблицы
		edit_data ($request, "requests");

}



// Добавление файлов
function insert_files($request_id, $table="files") {

	global $TranslationTypes, $filestoragepath;

		// Разбираем массив с данными о загруженных файлах
		foreach($_FILES['uploadfile']['name'] as $key=>$val) {

			// Массив с данными для файлов конкретных категорий
			foreach($_FILES['uploadfile']['name'][$key] as $keyint=>$valint) {

				// Если конкретный файл был загружен
				if ($valint) {

					// Подготовливаем данные для вставки в БД
					$details['translationtype_id'] = $key;
					$details['request_id'] = $request_id;
					$details['name'] = $valint;

					// Название файла
					if ($key == "1") $details['name'] = $request_id. "-" .$TranslationTypes[$key]. "-". $valint;
					
					// Копируем файл физически на сервере
					move_uploaded_file($_FILES['uploadfile']['tmp_name'][$key][$keyint], $filestoragepath.$details['name']);
					
					// Вставляем данные о нем в таблицу БД
					insert_data($details, $table);
				
				}

			}	
			
		}

}


// Отправка newsletter 23.11.2007 
function sendnewsletters() {

	global $Settings;

	$headers = 
		"From: $Settings[admin_name] <$Settings[email]>\r\n" .
		"Reply-To: $Settings[admin_name] <$Settings[email]>\r\n" .
		"Organization: $Settings[company_name]\r\n".
		"MIME-version: 1.0\n" .
		"Content-type: text/html; charset=\"UTF-8\"\r\n\r\n";

		$result = mysql_query("SELECT t.id,firstname,lastname, email,status_id FROM `translators` t,`requests` r WHERE r.status_id = 10 GROUP BY id LIMIT 0,5");

		for ($i=0; $i < mysql_num_rows($result); $i++)
	
			{

			$data = mysql_fetch_array($result);
			$startpoint = "Hello $data[firstname]\n\n";

			// mail("$data[firstname] $data[lastname]<$data[email]>", stripslashes($_POST['subject']), $startpoint.$_POST['content'], $headers);

			}

		mysql_query("UPDATE `newsletters` SET `status_id`='1', `date_sent` = NOW()");		

	return 1;

}

// Выделение строк с днями рождения другим цветом
function BirthDateIsCurrentDate ($date) {

	if (substr($date,5,2) == date('m')) return "id='today'";

}

// Используется для конвертации даты при генерации писем на основе шаблонов 
function CvtToDate($date, $minus=0) {
  
	$month_names=array("январь","февраль","март","апрель","май","июнь","июль","август","сентябрь","октябрь","ноябрь","декабрь"); 
	
	$date = date("j M Y", strtotime($date)-$minus);
	return $date;

}

// Получение имени по идентификатору $id в определенной таблице $table
function GetNameByIdAndTable ($id, $table, $field="name") {

	$respage = RunQueryReturnDataArray ($table, "WHERE id=$id", $field);
	return $respage[$field];

}

// Получение названия языковой пары по $id
function getLanguagePairById(&$id) {

	global $result;

		$f = RunQueryReturnDataArray ("pairs", "WHERE id=$id");

		$source = GetNameById (&$f['source'], 'languages', 'name');
		$target = GetNameById (&$f['target'], 'languages', 'name');
		$result = $source ." > ". $target;

	return $result;

}

// Получение названия идентификаторов языковой пары по $id
function getLanguagePairCodesById(&$id) {

	global $result;

		$f = RunQueryReturnDataArray ("pairs", "WHERE id=$id");

		$source = GetNameById (&$f['source'], 'languages', 'code');
		$target = GetNameById (&$f['target'], 'languages', 'code');
		$result = $source ."-". $target;

	return $result;

}

// Подсветка текущего раздела
function HighLightDefault($value, $SectionAction) {

	global $section, $action;
	if ($$SectionAction==$value)	echo "class=current";

}

function SubHeaderTabsHighlight($currentSection, $currentAction="default") {
	global $section, $action; 
	if ($section == $currentSection && $action  == $currentAction)		echo 'class=current';
}

// Получение данных о пользователе
function getUserDetails()	{

	global $UserDetails;
	$UserDetails = RunQueryReturnDataArray ("staff", "WHERE id=$_SESSION[staff_id]");

}

// Получение данных об описании каждого поля
function showHelpSign($hint) {

	echo "<img src='/mad/img/icons/question_mark.gif' title='$hint'> &nbsp; ";

}


// Вывод класса для парной строки
function PairedLineOrNot($number) {

	$pair=" class='pair'";
	if ($number%2 !== 0) $pair="";
	return $pair;

}

// Вывод строки текущего статуса проекта
// 24.10.2007
function showCurrentStatus($start="<div class='workflow'>") {

	global $f;

	$query = mysql_query("SELECT id,name,description FROM `status` LIMIT 0,10");

	for ($i=0; $i < mysql_num_rows($query); $i++) {

		$number = $i+1;
		$allstatuses = mysql_fetch_array($query);
	
		if ($allstatuses['id'] === $f['status_id']) { $current=" id='current'"; $description = $allstatuses['description']; $currnumber = $number; }
		elseif ($allstatuses['id'] > $f['status_id']) $current = " id='notcompleted'";
		else $current = " id='completed'";

		if ($i==0) $style = " style='margin-left: 0px;'";
		else $style = "";

		$start .= "<div class='workflowlist' title='$allstatuses[description]'$current{$style}><div>$number</div>$allstatuses[name]</div>";
	
	}

	echo $start."</div>
	<br clear='all'>
	<!-- <div style='font-size: 130%; margin: 5px 0;'><B>Step $currnumber:</B> $description</div> -->
	";


}

function convert_number($number) 
{ 
    if (($number < 0) || ($number > 999999999)) 
    { 
    throw new Exception("Number is out of range");
    } 

    $Gn = floor($number / 1000000);  /* Millions (giga) */ 
    $number -= $Gn * 1000000; 
    $kn = floor($number / 1000);     /* Thousands (kilo) */ 
    $number -= $kn * 1000; 
    $Hn = floor($number / 100);      /* Hundreds (hecto) */ 
    $number -= $Hn * 100; 
    $Dn = floor($number / 10);       /* Tens (deca) */ 
    $n = $number % 10;               /* Ones */ 

    $res = ""; 

    if ($Gn) 
    { 
        $res .= convert_number($Gn) . " Million"; 
    } 

    if ($kn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($kn) . " Thousand"; 
    } 

    if ($Hn) 
    { 
        $res .= (empty($res) ? "" : " ") . 
            convert_number($Hn) . " Hundred"; 
    } 

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", 
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
        "Nineteen"); 
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", 
        "Seventy", "Eighty", "Ninety"); 

    if ($Dn || $n) 
    { 
        if (!empty($res)) 
        { 
            $res .= " and "; 
        } 

        if ($Dn < 2) 
        { 
            $res .= $ones[$Dn * 10 + $n]; 
        } 
        else 
        { 
            $res .= $tens[$Dn]; 

            if ($n) 
            { 
                $res .= "-" . $ones[$n]; 
            } 
        } 
    } 

    if (empty($res)) 
    { 
        $res = "zero"; 
    } 

    return $res; 
} 



function sendingNewsletter () {

	$headers = 
		"From: Atlantic Silicon NewsLetters <hostingppcom@gmail.com>\r\n" .
		"BCC: $bcc\r\n".
		"MIME-version: 1.0\n" .
		"Content-type: text/html; charset=\"UTF-8\"\r\n\r\n";

		$content = nl2br($_POST['content']);

		$result = mysql_query("SELECT ID,email,name FROM `bookkeeping` WHERE `status` = '1'");
		mysql_query("UPDATE `bookkeeping` SET `read_count` = 0, `read_date` = '0000-00-00 00:00:00'");

		// $result = mysql_query("SELECT id,firstname,email FROM hostingpp.customers");

		for ($i=0; $i < mysql_num_rows($result); $i++)
	
			{

			$data = mysql_fetch_array($result);
			$custid = $data['ID'];
			$name = $data['name'];
			include "nl.php";

			if (mail($data['email'], stripslashes($_POST['subject']), $letter, $headers)) echo "$data[email] - OK<br>";
			else echo "$email - failed<br>";
			
			}

	return 1;

}



// Просто выполнение запроса в БД
function ExecSQL(&$sql) {
	if (mysql_query($sql)) return 1; else return 0;
}

// Получаем базовые параметры системы
getSettings();

$siteurl = "http://".$_SERVER['HTTP_HOST']."/mad/";


// Подключение модуля авторизации
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== "yes")  {
	include "login.php";
	exit();
} else getUserDetails();

?>