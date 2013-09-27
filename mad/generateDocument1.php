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


	$filename = "birth-certificate-" . trim($_POST['lastname']) . "-". trim($_POST['firstname']) . "-translation.doc";
	
	$content = file_get_contents("./templates/$template.rtf");

	$_POST['yearborn']			= substr($_POST['birthdate'],0,4);
	$_POST['monthborn']			= $monthNames[intval(substr($_POST['birthdate'],5,2))];
	$_POST['dayborn']			= substr($_POST['birthdate'],8,2);

	$_POST['dayborninwords']	= $dayNames[intval($_POST['dayborn'])];
	$_POST['yearborninwords']	= strtolower(convert_number($_POST['yearborn']));

	$_POST['yearissued']		= substr($_POST['dateofissue'],0,4);
	$_POST['monthissued']		= $monthNames[intval(substr($_POST['dateofissue'],5,2))];
	$_POST['dayissued']			= substr($_POST['dateofissue'],8,2);

	$_POST['paspyearissued']	= substr($_POST['dateofissuepassport'],0,4);
	$_POST['paspmonthissued']	= $monthNames[intval(substr($_POST['dateofissuepassport'],5,2))];
	$_POST['paspdayissued']		= substr($_POST['dateofissuepassport'],8,2);

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

	else $content = preg_replace("/(%%%GROUP3START)(.*?)(GROUP3END%%%)/i","",$content);

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


if (@$_POST['dosometh']=="validateform") generateRTFDocument("birth-certificate"); 

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
<h1>Birth Certificate Translation</h1>
<H2>About the document and the translation</H2>
<form method="post">

<? 

	$CopyOftheDoc = array(
		"isCopy" => "It's a copy of the document",
		"sourcelanguage" => "The source of the document is Ukrainian (default is Russian)",
		"isStampAboutPassportIssuance" => "A stamp about passport issuance is present",
		"enableApostille" => "Include Template for Apostile to the translation",
		"enableNotaryText" => "Include Notary's template text",
	);


GenerateCheckBox($CopyOftheDoc); ?>

<H2>About you</H2>

<?

GenerateInputTag("lastname","Last name as in your passport");
GenerateInputTag("firstname","First name as in your passport");
GenerateInputTag("middlename","Middle name");
GenerateInputTag("birthdate","Date of birth") 

?>
<script type="text/javascript">
	calendar.set("labelbirthdate");
</script>
<?

GenerateInputTag("placeofbirth","Place of birth");

?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('placeofbirth','City of ')">City of</span>
		&uarr; <span class="javascriptlink" onClick="setValue('placeofbirth','Village of ')">Village of</span><br>
</div>

<H2>Parents</H2>

<? GenerateInputTag("fathername","Full name of your father"); ?>

<div style='margin-left: 9px;'>
	&uarr; <span class="javascriptlink" onClick="setValue('fathername',document.forms['0'].lastname.value+' ')">Copy Last Name</span><br><br>
</div>

<? GenerateInputTag("nationalityf","His nationality (as shown in the document)"); ?>

<div style='margin-left: 9px;'>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalityf','Russian')">Russian</span>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalityf','Belarusian')">Belarusian</span>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalityf','Ukrainian')">Ukrainian</span><br><br><br><br>
</div>

<? GenerateInputTag("mothername","Full name of your mother"); ?>

<div style='margin-left: 9px;'>
	&uarr; <span class="javascriptlink" onClick="setValue('mothername',document.forms['0'].lastname.value+' ')">Copy Last Name</span><br><br>
</div>


<? GenerateInputTag("nationalitym","Her nationality (as shown in the document)"); ?>

<div style='margin-left: 9px;'>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalitym','Russian')">Russian</span>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalitym','Belarussian')">Belarussian</span>
	&uarr; <span class="javascriptlink" onClick="setValue('nationalitym','Ukrainian')">Ukrainian</span><br><br>
</div>


<H2>Legal information</H2>

<?

GenerateInputTag("registrationnumber","Registration number");
GenerateInputTag("registrationdate","Date of the registration");

?>

<script type="text/javascript">
	calendar.set("labelregistrationdate");
</script>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('registrationdate', document.forms['0'].birthdate.value)">Copy from date of birth date</span><br><br>
</div>

<?

GenerateInputTag("docseriesandnumber","Series and number");
GenerateInputTag("issuedby","Place of registration");

?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Civil Status Registration Branch Office of ')">Civil Status Registration Branch Office of </span><br>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Palace of solemnized events of ')">Palace of solemnized events of </span><br>
		&uarr; <span class="javascriptlink" onClick="setValue('issuedby','Palace of newborns of ')">Palace of newborns of </span><br><br>

</div>


<?
GenerateInputTag("dateofissue","Date of issue");
?>

<div style='margin-left: 9px;'>
		&uarr; <span class="javascriptlink" onClick="setValue('dateofissue', document.forms['0'].registrationdate.value)">Copy from date of registration</span><br><br>
</div>

<script type="text/javascript">
	calendar.set("labeldateofissue");
</script>

<?

GenerateInputTag("theregistrar","Name of the official person (if recognizible)");

?>

<h2>Stamps</h2>

<h3>Stamp about the passport issuance:</h3>
<?

GenerateInputTag("seriesandnumberpassport","Passport series and number");
GenerateInputTag("dateofissuepassport","Date of issue");

?>

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