<?

error_reporting(E_ALL);

include "../lib/shared.php";  
include "lib/lib.php";  



function generateRTFDocument($template) {

	$Languages	= array("Russian","Ukrainian");
	$isCopy		= array("","Copy");
	$monthNames = array("","January","February","March","April","May","June","July","August","September","October","November","December");
	$dayNames	= array("","first", "second", "third", "fourth", "fifth", "sixth", "seventh", "eighth", "nineth", "tenth", "eleventh", "twelfth", 
						"thirteenth", "fourteenth", "fifteenth", "sixteenth", "seventeenth", "eighteenth", "nineteenth", "twentieth", 
						"twenty first", "twenty second", "twenty third", "twenty fourth", "twenty fifth", "twenty sixth", "twenty seventh", 
						"twenty eighth", "twenty nineth", "thirtieth", "thirty first");


	$filename = "marriage-certificate-" . trim($_POST['husbandlastname']) . "-". trim($_POST['husbandfirstname']) . "-translation.doc";
	
	$content = file_get_contents("./templates/$template.rtf");

	// Husband
	$_POST['husbandyearborn']	= substr($_POST['husbandbirthdate'],0,4);
	$_POST['husbandmonthborn']	= $monthNames[intval(substr($_POST['husbandbirthdate'],5,2))];
	$_POST['husbanddayborn']	= substr($_POST['husbandbirthdate'],8,2);

	// Wife
	$_POST['wifeyearborn']		= substr($_POST['wifebirthdate'],0,4);
	$_POST['wifemonthborn']		= $monthNames[intval(substr($_POST['wifebirthdate'],5,2))];
	$_POST['wifedayborn']		= substr($_POST['wifebirthdate'],8,2);

	// Marriage
	$_POST['marriageyear']		= substr($_POST['marriagedate'],0,4);
	$_POST['marriagemonth']		= $monthNames[intval(substr($_POST['marriagedate'],5,2))];
	$_POST['marriageday']		= substr($_POST['marriagedate'],8,2);

	// Marriage date in words
	$_POST['marriagedayinwords']	= @$dayNames[intval($_POST['marriageday'])];
	$_POST['marriageyearinwords']	= strtolower(convert_number($_POST['marriagedate']));

	// Дата выдачи
	$_POST['yearissued']		= substr($_POST['dateofissue'],0,4);
	$_POST['monthissued']		= $monthNames[intval(substr($_POST['dateofissue'],5,2))];
	$_POST['dayissued']			= substr($_POST['dateofissue'],8,2);

	// Дата регистрации
	$_POST['yearregistered']	= substr($_POST['registrationdate'],0,4);
	$_POST['monthregistered']	= $monthNames[intval(substr($_POST['registrationdate'],5,2))];
	$_POST['dayregistered']		= substr($_POST['registrationdate'],8,2);

	$_POST['sourcelanguage']	= $Languages[$_POST['sourcelanguage']];
	$_POST['isCopy']			= $isCopy[$_POST['isCopy']];

	// Если включен блок выдачи паспорта
	if ($_POST['isStampAboutPassportIssuance']=="1") {

		$seriesandnumberpassport	= explode(" ", $_POST['seriesandnumberpassport']);

		$_POST['seriespassport']	= $seriesandnumberpassport['0'];
		$_POST['numberpassport']	= $seriesandnumberpassport['1'];

	}

	unset($_POST['birthdate'],$_POST["registrationdate"],$_POST["issuedate"]);

	// Производим замену переменных на правильные значения
	foreach($_POST as $key=>$val) {
		if (!empty($val)) $content = str_replace("%%%" . strtoupper($key) . "%%%", preg_replace('/(.*)_/','',trim($val)), $content);
	}

	$content = preg_replace('/%%%(\w+)%%%/i',"",$content);

    header('Content-Description: File Transfer');
	header("Content-type: text/richtext");
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
	echo $content;
//	die();

}


if (@$_POST['dosometh']=="validateform") generateRTFDocument("marriage-certificate"); 

?>
<html>
<head>
<link rel="stylesheet"		href="css/docGen.css"		type="text/css">
<link rel="stylesheet" href="/mad/css/calendar.css" type="text/css">
<script language="javascript" type="text/javascript" src="/mad/js/calendar_3.js"></script>
<script language="javascript" type="text/javascript" src="/mad/js/mm.js"></script>
</head>
<body>
<div class="block-center inner" style='width: 620px; margin:20px; padding: 20px; -moz-border-radius: 5px; background-color: white;'>
<h1>Marriage Certificate Translation</h1>
<H2>About the document and the translation</H2>
<form method="post">

<? 

	$CopyOftheDoc = array(
		"isCopy" => "It's a copy of the document",
		"sourcelanguage" => "The source of the document is Ukrainian (default is Russian)",

	);


GenerateCheckBox($CopyOftheDoc); ?>



<H2>Husband</H2>

<?

GenerateInputTag("husbandlastname","Last name as in your passport");
GenerateInputTag("husbandfirstname","First name as in your passport");
GenerateInputTag("husbandmiddlename","Middle name");
GenerateInputTag("husbandbirthdate","Date of birth") 

?>
<script type="text/javascript">
	calendar.set("labelhusbandbirthdate");
</script>
<? GenerateInputTag("husbandplaceofbirth","Place of birth"); ?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('husbandplaceofbirth','City of ')">City of</span>
		&uarr; <span class="javascriptlink" onClick="setValue('husbandplaceofbirth','Village of ')">Village of</span><br>
</div>



<H2>Wife</H2>

<?

GenerateInputTag("wifelastname","Last name as in your passport");
GenerateInputTag("wifefirstname","First name as in your passport");
GenerateInputTag("wifemiddlename","Middle name");
GenerateInputTag("wifebirthdate","Date of birth");

?>
<script type="text/javascript">
	calendar.set("labelwifebirthdate");
</script>
<? GenerateInputTag("wifeplaceofbirth","Place of birth"); ?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('wifeplaceofbirth','City of ')">City of</span>
		&uarr; <span class="javascriptlink" onClick="setValue('wifeplaceofbirth','Village of ')">Village of</span><br>
</div>



<H2>Marriage info</H2>

<? GenerateInputTag("marriagedate","Marriage date"); ?>
<script type="text/javascript">
	calendar.set("labelmarriagedate");
</script>
<? 

GenerateInputTag("registrationnumber","Registration number");
GenerateInputTag("registrationdate","Date of the registration");

?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('registrationdate',document.forms['0'].marriagedate.value)">Copy from registration date</span><br><br>
</div>

<H2>New last names</H2>

<? GenerateInputTag("newlastnamehusband","of the husband"); ?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('newlastnamehusband', document.forms['0'].husbandlastname.value)">Copy husband's last name</span><br><br>
</div>

<? GenerateInputTag("newlastnamewife","of the wife"); ?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('newlastnamewife', document.forms['0'].husbandlastname.value)">Copy husband's last name</span><br><br>
</div>



<H2>Legal information</H2>


<?

GenerateInputTag("docseriesandnumber","Series and number");
GenerateInputTag("issuedby","Place of registration");

?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Branch of the Civil Status Registration Office of ')">Branch of the Civil Status Registration Office of </span><br>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Wedding Registry Office of ')">Wedding Registry Office</span><br>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Palace of solemnized events of ')">Palace of solemnized events of </span><br>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Palace of newborns of ')">Palace of newborns of </span><br><br>

</div>


<?
GenerateInputTag("dateofissue","Date of issue");
?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('dateofissue', document.forms['0'].marriagedate.value)">Copy from date marriage date</span><br><br>
</div>

<script type="text/javascript">
	calendar.set("labeldateofissue");
</script>

<?

GenerateInputTag("theregistrar","Name of the official person (if recognizible)");

?>

<h2>Stamps</h2>

<h3>Text on the round seal:</h3>
<?

GenerateTextAreaTag("textontheroundseal");

?>

<script type="text/javascript">
	calendar.set("labeldateofissuepassport");
</script>
<br>

<input type="hidden" name="isStampAboutPassportIssuance" value="0" id="labelisStampAboutPassportIssuance">
<input type="hidden" name="sourcelanguage" value="0" id="labelsourcelanguage">
<input type="hidden" name="isCopy" value="0" id="labelisCopy">

<input type="hidden" name="dosometh" value="validateform">
<input type="submit" value="continue">
</form>

</div>

</body>
</html>