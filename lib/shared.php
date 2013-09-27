<?

// Выборки из БД. Если есть значения, то возвращаем их, если нет, то возвращаем bool об ошибке
function ProcessSQL ($table, $more="", $column="*",$showsql="0") {

	$sql = "SELECT $column FROM $table $more";
	if ($showsql == "1") echo $sql;

	$res = mysql_fetch_array(mysql_query($sql));
	if ($res['0']) {
		return $res['0'];
	}
	else return 0;

	print_r($res);

}

function GetFinancialTotalForYear($year,$isYear="year") {

	$currYear = date("Y");

	$Period = array(
		"year" => 	"WHERE (`registrationtime` BETWEEN '$year-01-01 00:00:00' AND '$year-12-31 23:59:00')  AND `status_id` = '10'",
		"month" => 	"WHERE (`registrationtime` BETWEEN '$currYear-$year-01 00:00:00' AND '$currYear-$year-31 23:59:00')  AND `status_id` = '10'",
	);

	$result = RunQueryReturnDataArray(

		"requests",

		$Period[$isYear], 

		"(SUM(`amountpaid`) - ROUND(SUM((`amountpaid` * 0.029 )),2)) - ROUND(SUM((ppwt*wordcount) + (ppwp*wordcount)),2) as PreNetAmount, 
		count('staus_id') as NumberOfOrders,
		ROUND(SUM((ppwt*wordcount) + (ppwp*wordcount)),2) as workerFees,
		SUM(`amountpaid`) as totalAmountPaid"
		); 
	
		$result['NetAmount'] = round($result['PreNetAmount'] - ($result['NumberOfOrders']*0.30),2);
		$result['PayPalFees'] = round(($result['totalAmountPaid'] * 0.029) + ($result['NumberOfOrders']*0.30),2);
		return $result;

}


// Выборки из БД. Если есть значения, то возвращаем их, если нет, то возвращаем bool об ошибке
// 12.10.2007
function RunQueryReturnDataArray ($table, $more="", $column="*",$showsql="0") {

	$sql = "SELECT $column FROM $table $more";
	if ($showsql == "1") 
		echo $sql."<br><br>";
	return mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);

}

// Выборки из БД. Если есть значения, то возвращаем их, если нет, то возвращаем bool об ошибке
// 12.10.2007
function RunSelectFromTableQuery($table, $more="", $column="*") {

	$sql = "SELECT $column FROM `$table` $more";
	// echo $sql;
	return mysql_query($sql);

}


// Ошибки и уведомления системы
function ErrorMsg () {
	global $error_msg, $status;
	if (!empty($error_msg))	echo "<div class='error_msg' id='$status'> $error_msg</div>"; 
}

// Если значение переменной существует в каком-либо виде - отобразить
// 24.07.2010
function ifExistGetValue($valuename,$YesOrNo=0,$res="") {

	global $f, $action, $YesOrNoArray;
	
	if (!empty($_REQUEST[$valuename])) 
		$res = $_REQUEST[$valuename];

	elseif (!empty($f[$valuename]))
		$res = $f[$valuename];

		if ($YesOrNo == 0 || $res == NULL) echo $res; else echo $YesOrNoArray[$res];

}

// Если значение переменной существует в каком-либо виде - отобразить
// 24.07.2010
function ifExistGetValue2($valuename,$YesOrNo=0,$res="") {

	global $f, $action, $YesOrNoArray;
	
	if (!empty($_REQUEST[$valuename])) 
		$res = $_REQUEST[$valuename];

	elseif (!empty($f[$valuename]))
		$res = $f[$valuename];

		if ($YesOrNo == 0 || $res == NULL) return $res; else return $YesOrNoArray[$res];

}

// Получить требуемое значение из таблицы по идентификатору
function GetNameById (&$id, $table, $name) {

	$res = RunQueryReturnDataArray($table, "WHERE `id`='$id'");
	return $res[$name];

}

// Если было выбрано ранее значение поля select - поставить аттрибут selected
function select($field, $number)	{

	global $f;

	// echo $field . " - ". $number;
	if($f[$field] == $number || (!empty($_POST[$field]) && $_POST[$field] == $number)) echo " selected";

}

// Генерация списка селекта
function GenerateSelectTag($from,$columntoselect,$orderbyname="name", $selecttag="") {

		$res = mysql_query("select * from `$from` ORDER BY `$orderbyname`");

		while($col = mysql_fetch_array($res))
			{
				$selecttag .= "\t\t\t<option value=\"$col[id]\"" . selectv2('area_id', $col['id']) . ">$col[name]</option>\n";
			}

		return $selecttag;

}

// Если было выбрано ранее значение поля select - поставить аттрибут selected
function selectv2($field, $number)	{

	global $f;

	if($f[$field] == $number || (!empty($_POST[$field]) && $_POST[$field] == $number)) return " selected";

}


// Генерация тега select
// 03.08.2007
// Название поля
function GenerateSelectList($WhatWhatTableToSelect, $nameOfIdentificatorAutoToSelect, $nameofvaluetoshow, $description="", $separator=" &nbsp; ")	{

	$res = mysql_query("select * from `$WhatWhatTableToSelect` ORDER BY $nameofvaluetoshow");

	$select = "<select name='$nameOfIdentificatorAutoToSelect' id='label$nameOfIdentificatorAutoToSelect'>";
	
	while($col = mysql_fetch_array($res))	{
		$select .= "\t\t<option value='".$col['id']."'";
		$select .= selectv2($nameOfIdentificatorAutoToSelect, $col['id']);
		$select .= ">$col[$nameofvaluetoshow]</option>\n";
	}

	echo $select."</select>
	 $separator <label for='label$nameOfIdentificatorAutoToSelect'>$description</label>
	
	";

}

function GenerateCheckBox($array,$separator=" &nbsp; ",$br="<br>", $js="",$var=""){

	global $checkedOrNot;

	foreach($array  as $key=>$description)	{

	@$var .= "<input type='checkbox' id='$key' " . $checkedOrNot[ifExistValueReturnIt($key)] . " onClick='ChangeHiddenFieldValue(\"$key\")' $js><label for='$key'> $separator $description &nbsp; </label>\n$br";

	}

	echo $var;

}

function GenerateCheckBoxV2($array,$separator=" &nbsp; ",$br="<br>", $js="",$var=""){

	global $checkedOrNot;

	foreach($array  as $key=>$description)	{

	@$var .= "<input type='checkbox' id='$key' " . $checkedOrNot[ifExistValueReturnIt($key)] . " onClick='ChangeHiddenFieldValue(\"$key\")' $js>
	<label for='$key'> $separator $description &nbsp; </label>
	<input type='hidden' name='$key' value='" . ifExistValueReturnIt($key) . "' id='label$key' >\n$br
	";

	}

	echo $var;

}

// Генерация тега input
// 29.08.2010
// 08.11.2009
function GenerateInputTag($name,$description, $type="text", $separator=" &nbsp; ",$br="<br>", $js="")	{

	$label = "<label for='label$name'>$description</label> &nbsp; <span class='hint' id='innerHTML$name' onclick='this.style.display=\"none\"'></span>$br";
	if ($type=="hidden") { $br = $label = ""; }
    echo "\n<input type='$type' name='$name' value='" . ifExistValueReturnIt($name) . "' id='label$name' $js> $separator $label";

}

// Генерация тега textarea
// 03.10.2007
function GenerateTextAreaTag($name)	{

	echo "<textarea name='$name'>" . ifExistValueReturnIt($name) . "</textarea>";

}


// Проверка правильности заполнения полей
// 17.07.2007
function IsRequiredFieldsFilled($RequiredFielsArray) {

	global $error_msg;

		foreach($RequiredFielsArray as $key=>$value)	{
			if (empty($_POST[$key])) $error_msg .= "The field \"<B>$value</B>\" must be filled.<br>";
		}
			if (empty($error_msg)) return 1;
			else return 0;
}

// Если переменная существует - выводим ее
// 29.08.2007
function ifExistValueReturnIt($valuename) {

	global $f;

	if (isset($_POST[$valuename])) 
		return $_POST[$valuename]; 
	else return @$f[$valuename];


}

// Отправка почты
function sendmail ()	{

	global $Settings, $error_msg;

	// echo $_POST['toemail'];

	$headers = 
		"From: $_POST[fromname] <$_POST[fromemail]>\r\n" .
		"Reply-To: $_POST[fromname] <$_POST[fromemail]>\r\n" .
		"Organization: $Settings[company_name]\r\n".
		"MIME-version: 1.0\n" .
		"Content-type: text/html; charset=\"UTF-8\"\r\n\r\n";

		if (mail("$_POST[toname] <$_POST[toemail]>", $_POST['subject'], html_entity_decode($_POST['content']), $headers)) $error_msg = "Message Sent Successfully";
			else $error_msg = "Message Sending Failed";

	return $error_msg;

}


// Отправка почты с вложениями
// 17.10.2007
// $ct = выбор типа контента, по умолчанию HTML, 1 - для смешанного типа
function sendmail2 ($ctype="0")	{

	global $Settings, $error_msg, $filestoragepath;

	// Пока не знаю для чого це потрібно, але вроде розділювач між прикріпленими файлами
	$mime_boundary = "==Multipart_Boundary_x{" . md5(time()) . "}x";

	// Текст письма
	$content = html_entity_decode($_POST['content']);

	// Массив с двуями видами шапками - просто HTML и смешанный (текст + аттачи)
	$contenttype = array(
		"Content-type: text/html; charset=\"UTF-8\"\r\n\r\n",
        "Content-Type: multipart/mixed; " . 
        "boundary=\"{$mime_boundary}\";\n"
		// "Content-Transfer-Encoding: 7bit\n\n"
	);
	
	// Если есть файлы то подставляем соотв. другой хедер, а также прикреп. файлы
	if ($_POST['filelisting']) {

		$ctype = "1";
		
		// указываем наличие файлов
		$content .= "This is a multi-part message in MIME format.\n\n" . 
        "--{$mime_boundary}\n" . 
        "Content-Type; multipart/mixed\n" . 
        "Content-Transfer-Encoding: 7bit\n\n";

		// Получаем массив из названий файлов, полученной из переменной POST
		$filelisting = explode(";", $_POST['filelisting']);

		// Убиваем последний элемент в этом массиве
		array_pop($filelisting);

		// Тип файла по умаолчанию
		$fileatt_type = "application/octet-stream"; // File Type 

		// Разбираем массив с файлами
		foreach($filelisting as $key=>$value) {

			// Получаем полный путь к файлу
			$filetoread = $filestoragepath.trim($value);

			// Открываем его
			$file = fopen($filetoread,'rb'); 

			// Считываем в буфер
			$data = fread($file,filesize($filetoread)); 

			// Закрываем
			fclose($file);

			// Конвертируем в емейл формат
			$data = chunk_split(base64_encode($data)); 
			
			// Загоняем сконвертированные данные в текст письма
			$content .= "\n\n--{$mime_boundary}\n" . 
					  "Content-Type: {$fileatt_type};" . 
					  " name=\"" . trim($value) . "\";\n" . 
					  "Content-Transfer-Encoding: base64\n" . 
					  "Content-Disposition: attachment;\n\n" . 
					 $data;
	
		}

		$content .= "--{$mime_boundary}--\n";

	}

	// Формирование шапки в зависимости от наличия файлов
	$headers = 
		"From: $_POST[fromname] <$_POST[fromemail]>\r\n" .
		"Reply-To: $_POST[fromname] <$_POST[fromemail]>\r\n" .
		"Organization: $Settings[company_name]\r\n".
		"MIME-version: 1.0\n".
		$contenttype[$ctype];	

		if (mail("$_POST[toname] <$_POST[toemail]>", $_POST['subject'], $content, $headers)) $error_msg = "Message Sent Successfully";
			else $error_msg = "Message Sending Failed";
			

	return $error_msg;

}


// Получение общих параметров системы
function getSettings()	{

	global $Settings;
	$Settings = mysql_fetch_array(mysql_query("SELECT * FROM settings WHERE id='1'"));

}

function ApplyTemplate($template="",$details)	{

	$content = file_get_contents("tpl/$template.html");

		// Удаляем значения стоимости слова в начале ключа area
		foreach($details as $key=>$val) {
			if (!empty($val)) $content = str_replace("%%%" . strtoupper($key) . "%%%", preg_replace('/(.*)_/','',$val), $content);
		}

		// Меняем оставшиеся и необработанные переменные на n/a
		return preg_replace('/%(.*)%/','n/a',$content);

}

// Генерация списка селекта
function GenerateLinksList($table,$more="",$orderbyname="name",$columntoselect="*",$selecttag="", $orderby="") {

		if (!empty($orderbyname)) $orderby =  "ORDER BY `$orderbyname`";

		$sql="SELECT $columntoselect FROM `$table` $more $orderby";
		$res = mysql_query($sql);
		echo mysql_error();

		// echo $sql;

		switch($table) {
		
		default: break;

		case "rates": 

			while($col = mysql_fetch_array($res)) { $selecttag .= "\t\t\t<li><a href='requests/?area_id=$col[id]'>$col[name]</a></li>\n";}

			break;

		case "translators": 

			while($col = mysql_fetch_array($res)) { 
				$age = date("Y-m-00") - $col['birthdate'];
				$selecttag .= "\t\t\t<li><a href='translators/?email=$col[email]'>$col[firstname] $col[lastname]</a> <span title='$col[birthdate]'>($age)</span></li>\n";
				}
			return $selecttag;
			break;
		
		}
			return $selecttag;

}

?>