<?

// Âûáîðêè èç ÁÄ. Åñëè åñòü çíà÷åíèÿ, òî âîçâðàùàåì èõ, åñëè íåò, òî âîçâðàùàåì bool îá îøèáêå
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


// Âûáîðêè èç ÁÄ. Åñëè åñòü çíà÷åíèÿ, òî âîçâðàùàåì èõ, åñëè íåò, òî âîçâðàùàåì bool îá îøèáêå
// 12.10.2007
function RunQueryReturnDataArray ($table, $more="", $column="*",$showsql="0") {

	$sql = "SELECT $column FROM $table $more";
	if ($showsql == "1") 
		echo $sql."<br><br>";
	return mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);

}

// Âûáîðêè èç ÁÄ. Åñëè åñòü çíà÷åíèÿ, òî âîçâðàùàåì èõ, åñëè íåò, òî âîçâðàùàåì bool îá îøèáêå
// 12.10.2007
function RunSelectFromTableQuery($table, $more="", $column="*") {

	$sql = "SELECT $column FROM `$table` $more";
	// echo $sql;
	return mysql_query($sql);

}


// Îøèáêè è óâåäîìëåíèÿ ñèñòåìû
function ErrorMsg () {
	global $error_msg, $status;
	if (!empty($error_msg))	echo "<div class='error_msg' id='$status'> $error_msg</div>"; 
}

// Åñëè çíà÷åíèå ïåðåìåííîé ñóùåñòâóåò â êàêîì-ëèáî âèäå - îòîáðàçèòü
// 24.07.2010
function ifExistGetValue($valuename,$YesOrNo=0,$res="") {

	global $f, $action, $YesOrNoArray;
	
	if (!empty($_REQUEST[$valuename])) 
		$res = $_REQUEST[$valuename];

	elseif (!empty($f[$valuename]))
		$res = $f[$valuename];

		if ($YesOrNo == 0 || $res == NULL) echo $res; else echo $YesOrNoArray[$res];

}

// Åñëè çíà÷åíèå ïåðåìåííîé ñóùåñòâóåò â êàêîì-ëèáî âèäå - îòîáðàçèòü
// 24.07.2010
function ifExistGetValue2($valuename,$YesOrNo=0,$res="") {

	global $f, $action, $YesOrNoArray;
	
	if (!empty($_REQUEST[$valuename])) 
		$res = $_REQUEST[$valuename];

	elseif (!empty($f[$valuename]))
		$res = $f[$valuename];

		if ($YesOrNo == 0 || $res == NULL) return $res; else return $YesOrNoArray[$res];

}

// Ïîëó÷èòü òðåáóåìîå çíà÷åíèå èç òàáëèöû ïî èäåíòèôèêàòîðó
function GetNameById (&$id, $table, $name) {

	$res = RunQueryReturnDataArray($table, "WHERE `id`='$id'");
	return $res[$name];

}

// Åñëè áûëî âûáðàíî ðàíåå çíà÷åíèå ïîëÿ select - ïîñòàâèòü àòòðèáóò selected
function select($field, $number)	{

	global $f;

	// echo $field . " - ". $number;
	if($f[$field] == $number || (!empty($_POST[$field]) && $_POST[$field] == $number)) echo " selected";

}

// Ãåíåðàöèÿ ñïèñêà ñåëåêòà
function GenerateSelectTag($from,$columntoselect,$orderbyname="name", $selecttag="") {

		$res = mysql_query("select * from `$from` ORDER BY `$orderbyname`");

		while($col = mysql_fetch_array($res))
			{
				$selecttag .= "\t\t\t<option value=\"$col[id]\"" . selectv2('area_id', $col['id']) . ">$col[name]</option>\n";
			}

		return $selecttag;

}

// Åñëè áûëî âûáðàíî ðàíåå çíà÷åíèå ïîëÿ select - ïîñòàâèòü àòòðèáóò selected
function selectv2($field, $number)	{

	global $f;

	if($f[$field] == $number || (!empty($_POST[$field]) && $_POST[$field] == $number)) return " selected";

}


// Ãåíåðàöèÿ òåãà select
// 03.08.2007
// Íàçâàíèå ïîëÿ
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

// Ãåíåðàöèÿ òåãà input
// 29.08.2010
// 08.11.2009
function GenerateInputTag($name,$description, $type="text", $separator=" &nbsp; ",$br="<br>", $js="")	{

	$label = "<label for='label$name'>$description</label> &nbsp; <span class='hint' id='innerHTML$name' onclick='this.style.display=\"none\"'></span>$br";
	if ($type=="hidden") { $br = $label = ""; }
    echo "\n<input type='$type' name='$name' value='" . ifExistValueReturnIt($name) . "' id='label$name' $js> $separator $label";

}


// Ãåíåðàöèÿ òåãà textarea
// 03.10.2007
function GenerateTextAreaTag($name)	{

	echo "<textarea name='$name'>" . ifExistValueReturnIt($name) . "</textarea>";

}


// Ïðîâåðêà ïðàâèëüíîñòè çàïîëíåíèÿ ïîëåé
// 17.07.2007
function IsRequiredFieldsFilled($RequiredFielsArray) {

	global $error_msg;

		foreach($RequiredFielsArray as $key=>$value)	{
			if (empty($_POST[$key])) $error_msg .= "The field \"<B>$value</B>\" must be filled.<br>";
		}
			if (empty($error_msg)) return 1;
			else return 0;
}

// Åñëè ïåðåìåííàÿ ñóùåñòâóåò - âûâîäèì åå
// 29.08.2007
function ifExistValueReturnIt($valuename) {

	global $f;

	if (isset($_POST[$valuename])) 
		return $_POST[$valuename]; 
	else return @$f[$valuename];


}

// Îòïðàâêà ïî÷òû
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


// Îòïðàâêà ïî÷òû ñ âëîæåíèÿìè
// 17.10.2007
// $ct = âûáîð òèïà êîíòåíòà, ïî óìîë÷àíèþ HTML, 1 - äëÿ ñìåøàííîãî òèïà
function sendmail2 ($ctype="0")	{

	global $Settings, $error_msg, $filestoragepath;

	// Ïîêà íå çíàþ äëÿ ÷îãî öå ïîòð³áíî, àëå âðîäå ðîçä³ëþâà÷ ì³æ ïðèêð³ïëåíèìè ôàéëàìè
	$mime_boundary = "==Multipart_Boundary_x{" . md5(time()) . "}x";

	// Òåêñò ïèñüìà
	$content = html_entity_decode($_POST['content']);

	// Ìàññèâ ñ äâóÿìè âèäàìè øàïêàìè - ïðîñòî HTML è ñìåøàííûé (òåêñò + àòòà÷è)
	$contenttype = array(
		"Content-type: text/html; charset=\"UTF-8\"\r\n\r\n",
        "Content-Type: multipart/mixed; " . 
        "boundary=\"{$mime_boundary}\";\n"
		// "Content-Transfer-Encoding: 7bit\n\n"
	);
	
	// Åñëè åñòü ôàéëû òî ïîäñòàâëÿåì ñîîòâ. äðóãîé õåäåð, à òàêæå ïðèêðåï. ôàéëû
	if ($_POST['filelisting']) {

		$ctype = "1";
		
		// óêàçûâàåì íàëè÷èå ôàéëîâ
		$content .= "This is a multi-part message in MIME format.\n\n" . 
        "--{$mime_boundary}\n" . 
        "Content-Type; multipart/mixed\n" . 
        "Content-Transfer-Encoding: 7bit\n\n";

		// Ïîëó÷àåì ìàññèâ èç íàçâàíèé ôàéëîâ, ïîëó÷åííîé èç ïåðåìåííîé POST
		$filelisting = explode(";", $_POST['filelisting']);

		// Óáèâàåì ïîñëåäíèé ýëåìåíò â ýòîì ìàññèâå
		array_pop($filelisting);

		// Òèï ôàéëà ïî óìàîë÷àíèþ
		$fileatt_type = "application/octet-stream"; // File Type 

		// Ðàçáèðàåì ìàññèâ ñ ôàéëàìè
		foreach($filelisting as $key=>$value) {

			// Ïîëó÷àåì ïîëíûé ïóòü ê ôàéëó
			$filetoread = $filestoragepath.trim($value);

			// Îòêðûâàåì åãî
			$file = fopen($filetoread,'rb'); 

			// Ñ÷èòûâàåì â áóôåð
			$data = fread($file,filesize($filetoread)); 

			// Çàêðûâàåì
			fclose($file);

			// Êîíâåðòèðóåì â åìåéë ôîðìàò
			$data = chunk_split(base64_encode($data)); 
			
			// Çàãîíÿåì ñêîíâåðòèðîâàííûå äàííûå â òåêñò ïèñüìà
			$content .= "\n\n--{$mime_boundary}\n" . 
					  "Content-Type: {$fileatt_type};" . 
					  " name=\"" . trim($value) . "\";\n" . 
					  "Content-Transfer-Encoding: base64\n" . 
					  "Content-Disposition: attachment;\n\n" . 
					 $data;
	
		}

		$content .= "--{$mime_boundary}--\n";

	}

	// Ôîðìèðîâàíèå øàïêè â çàâèñèìîñòè îò íàëè÷èÿ ôàéëîâ
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


// Ïîëó÷åíèå îáùèõ ïàðàìåòðîâ ñèñòåìû
function getSettings()	{

	global $Settings;
	$Settings = mysql_fetch_array(mysql_query("SELECT * FROM settings WHERE id='1'"));

}

function ApplyTemplate($template="",$details)	{

	$content = file_get_contents("tpl/$template");

//	echo $template;

		// Óäàëÿåì çíà÷åíèÿ ñòîèìîñòè ñëîâà â íà÷àëå êëþ÷à area
		foreach($details as $key=>$val) {
			if (!empty($val)) $content = str_replace("%%%" . strtoupper($key) . "%%%", preg_replace('/(.*)_/','',$val), $content);
		}

		// Ìåíÿåì îñòàâøèåñÿ è íåîáðàáîòàííûå ïåðåìåííûå íà n/a
		return preg_replace('/%(.*)%/','n/a',$content);

}

// Ãåíåðàöèÿ ñïèñêà ñåëåêòà
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


function num2str($num) {

	$num = round($num);

    $nul='íîëü';
    $ten=array(
        array('','îäèí','äâà','òðè','÷îòèðè','ï\'ÿòü','ø³ñòü','ñ³ì', 'â³ñ³ì','äåâ\'ÿòü'),
        array('','îäíà','äâ³','òðè','÷îòèðè','ïÿòü','ø³ñòü','ñ³ì', 'â³ñ³ì','äåâ\'ÿòü'),
    );
    $a20=array('äåñÿòü','îäèíàäöÿòü','äâàíàäöÿòü','òðèíàäöÿòü','÷îòèðíàäöàòü' ,'ïÿ\'òíàäöàòü','ø³ñòíàäöÿòü','ñ³ìíàäöÿòü','â³ñ³ìíàäöàòü','äåâÿ\'òíàäöÿòü');
    $tens=array(2=>'äâàäöÿòü','òðèäöÿòü','ñîðîê','ïÿ\'òäåñÿò','ø³ñòäåñÿò','ñ³ìäåñÿò' ,'â³ñ³ìäåñÿò','äåâ\'ÿíîñòî');
    $hundred=array('','ñòî','äâ³ñò³','òðèñòà','÷îòèðèñòà','ï\'ÿòñîò','ø³ñòñîò', 'ñ³ìñîò','â³ñ³ìñîò','äåâ\'ÿòñîò');
    $unit=array( // Units
        array('êîï³éêà' ,'êîï³éêè' ,'êîï³éîê',	 1),
        array('ãðèâíÿ'   ,'ãðèâí³'   ,'ãðèâåíü'    ,0),
        array('òèñÿ÷à'  ,'òèñÿ÷³'  ,'òèñÿ÷'     ,1),
        array('ì³ëüéîí' ,'ì³ëüéîíà','ì³ëüéîí³â' ,0),
        array('ì³ëüÿðä','ì³ëüÿðäà','ì³ëüÿðä³â',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

?>