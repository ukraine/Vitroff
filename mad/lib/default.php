<?

// echo $section.$action;

switch($action) {

default:

	$action = "default";
	$title = ucfirst($section) . " &#151; viewing the list";

	// Èíèöèàëèçàöèÿ ïåðìåííûõ
	$where = $qeuryforpaginator = $paging = "";

		// Ïîëó÷åíèå äîïîëíèòåëüíûõ ïàðàìåòðîâ
		parse_str($_SERVER['QUERY_STRING'],$query);
		foreach($query as $key=>$val)

				{
					if ($key !== "section" & $key !== "page" & $key !== "sortby" & $key !== "ascdesc" && $key !=="param") {
						
						// Äîïîëíèòåëüíàÿ ñòðîêà äëÿ çàïðîñà â ÁÄ
						$where =" WHERE $key = '$val'";

					}

					if ($key !== "section" & $key !== "page") {
												
						// Äëÿ ïàãèíàòîðà
						$qeuryforpaginator .= "$key=$val&";

					}

				}

	$url = $_SERVER['REDIRECT_URL'] . "?" . $qeuryforpaginator;

	// Ôîðìèðîâàíèå ñóòè íàøåãî çàïðîñà
	$sql = "FROM `$section` $where ORDER BY `$orderby` $ascdesc";

	// echo $sql;

	// Ñ÷èòàåì îáùåå êîë-âî îáúåêòîâ
	$res = @mysql_fetch_array(mysql_query("select count(*) as count $sql"));

	// Åñëè õîòü ÷òî-òî åñòü, äàëüøå âûâîäèì ñïèñîê
	if($res) {
		
		// Âñåãî çàïèñåé
		$count = $res['count'];

		// Ñ÷èòàåì îáùåå êîë-âî ñòðàíèö
		$totalpages = ceil($count/$Settings['itemsonpage']);

		$page = 1;
		
		// óçíàåì íà êàêîé ñòðàíèöå íàõîäèìñÿ
		if(!empty($_GET['page'])) $page = $_GET['page'];

		// echo "page: $page<BR>";

		// Âûñòàâëåíèå ëèìèòà êîë-âà îáúåêòîâ íà ñòðàíèöå (òàê æå èñï-çóåòñÿ äëÿ íóìåðàöèè îáú. íà ñòðàíèöàõ)
		$startlimit = ($Settings['itemsonpage']*$page) - $Settings['itemsonpage'];

		// $startlimitv2 = $startlimit-$Settings['itemsonpage'];

		// echo "LIMIT $startlimit, XX<BR>";

		// Ïåðâûé îáúåêò íà ñòðàíèöå
		$startobject = $startlimit + 1;

		// Ïîñëåäíèé îáúåêò íà ñòðàíèöå
		$endobject = $startobject + $Settings['itemsonpage'] - 1;

		// Åñëè îáúåêòîâ ìåíüøå, ÷åì ðàçðåøåíî íà ñòðàíèöå
		if ($count <= $Settings['itemsonpage']) $endobject = $count;
		
		// Èñï. äëÿ ïîñëåäíåé ñòðàíèöû
		if ($endobject > $count) $endobject = $count ;

		// Ôîðìèðîâàíèå êîíå÷íîãî çàïðîñà
		$sql = $sql . " LIMIT $startlimit, $Settings[itemsonpage]";

		// echo $sql;

		// Ñîáñòâåííî äåëàåì âûáîðêó
		$res = mysql_query("SELECT * $sql");


	}

	break;

// Ñòðàíè÷êà äîáàâëåíèÿ ýëåìåíòà
case "add":

		$action = "addedit";
		$title = ucfirst($section) . " &#151; adding new";
		$displayitwithsourcetext = "none";

	break;

// Âûïîëíåíèå äîáàâëåíèÿ
case "do_add":

	// DEMO VERSION LIMITATIONS

	$title = "DEMONSTRATION VERSION LIMITATIONS";

	if ($section == "status" || $section == "templates") {

		$error_msg = "Sorry. This feature is disabled in the demo-version";
		$section = "default";
		$action = "demo";

	}

		else {

	// DEMO VERSION LIMITATIONS

	$action = "addedit";
	$title = ucfirst($section) . " &#151; adding new";
	$displayitwithsourcetext = "none";

		// Ïðîâåðÿåì ïîëó÷àåìûå äàííûå íà êîððåêòíîñòü è åñëè âñå õîðîøî, ââîäèì äàííûå
		if (IsRequiredFieldsFilled($SectionsRequiredFields[$section]) ) {

			// REQUESTS: Åñëè åñòü ïîëå ñ èñõ. òåêñòîì òî ïîäñ÷èòûâàåì ñëîâà 
			if (!empty($_POST['source_text'])) $_POST['wordcount'] = str_word_count($_POST['source_text']);

			// Óïðîùàåì ðàáîòó ñ èìåíàìè
			/* if ($section == "customers" && !empty($_POST['firstnamelastname'])) {
				$name = explode($_POST['firstnamelastname']," "); 
				$_POST['lastname'] = $name[1];
				$_POST['firstname'] = $name[0];
				} */

			// ALL: Âðåìåííàÿ ìåòêà ïîñòóïëåíèÿ
			$_POST['registrationtime'] = date("Y-m-d H:m:s");

			// Âñòàâëÿåì äàííûå â áàçó äàííûõ
			if (insert_data($_POST, $section)) {

				// Åñëè ïðèñóòñòâóþò ôàéëû, äîáàâëÿåì òàêæå è èõ
				if ($_FILES) insert_files(ProcessSQL ('requests', "ORDER BY `id` DESC LIMIT 0,1"));

				// Åñëè íàæàòà êíîïêà "Continue Edit", òî óçíàåì ID òîëüêî ÷òî ñîçäàííîé ñòðàíèöû è ïðîäîëæàåì ðåäàêòèðîâàíèå
				if (!empty($_POST['submit'])) {
					$action = "addedit";
					header("Location: {$siteurl}$section/edit/" . ProcessSQL ('requests', "ORDER BY `id` DESC LIMIT 0,1"));
					break;
				}

				// Åñëè îòêðûòî âñïëûâàþùåå îêíî
				if ($pagetemplate=="popup") {
					$section	= "default"; 
					$action		= "popupclose"; 
					$error_msg	= "Operation completed";
				}

				// Â ïðîòèâíîì ñëó÷àå âûâîäèì 
				else header("Location: {$siteurl}$section/");

			}	
			
			// Åñëè íå ñìîãëè âíåñòè äàííûå ïî êàêèì-ëèáî ïðè÷èíàì - âûâîäèì ñîîáùåíèå îá îøèáêå
			else {
				$title = "Error";
				$error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();
			}

		}

		}	// DEMO VERSION LIMITATIONS LINE

	break;

// Ñòðàíè÷êà ðåäàêòèðîâàíèÿ ýëåìåíòà
case "edit":

	$action = "addedit";
	$title = ucfirst($section) . " &#151; editing existing";
	$displayitwithsourcetext = "none";

	// Äåëàåì âûáîðêó ñ äàííûìè î êîíêðåòíîé îáúåêòå
	$f = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");

	if (!empty($f['source_text'])) $displayitwithsourcetext="inline";

	break;

// Âûïîëíåíèå ðåäàêòèðîâàíèÿ
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
	$title = ucfirst($section) . " &#151; editing existing";

	$location = $siteurl.$section."/";

			// Åñëè çàãðóæàëèñü êàêèå-ëèáî ôàéëû, çàïèñûâàåì èõ â îïðåäåëåííóþ äèðåêòîðèþ
			// à èíôîðìàöèþ î íèõ â áàçó äàííûõ
			
			if ($_FILES)		insert_files($_GET['id']);

	// Åñëè â ïîëå "èñõîäíûé òåêñò" åñòü òåêñò, òî ñ÷èòàåì êîë-âî ñëîâ, çàíîñÿ èõ â ïåðåìåííóþ
	if (!empty($_POST['source_text'])) $_POST['wordcount'] = str_word_count($_POST['source_text']);


	// 08.11.2009 Åñëè áûëà óêàçàíà ñóììà â ïîëå "îïëà÷åíî" ðàçäåëà çàïðîñû ïðè ðåäàêòèðîâàíèè òî ìåíÿåì ñòàòóñ íà îïëà÷åíî
	if ($section == "requests" && $_POST['amountpaid'] > 0 && $_POST['status_id'] <= 2) $_POST['status_id'] = "3";

	// "Ñìåíà ïàðîëÿ": Åñëè ìû íà ñòðàíèöå èçìåíåíèÿ ïàðîëÿ, òî øèôðóåì åãî è çàíîñèì â òàáëèöó
	if (!empty($_POST['password']) && $_POST['password']!== $UserDetails['password']) {
		$_POST['password'] = md5($_POST['password']);
	} else	unset($_POST['password']);

	// Êíîïêà "Continue Edit" - ïðîäîëæåíèå ðåäàêòèðîâàíèÿ ïîñëå ñîõðàíåíèÿ
	if (!empty($_POST['submit'])) $location = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

	// Ñîõðàíÿåì äàííûå è âîçâðàùàåìñÿ íà ãëàâíóþ ñòðàíèöó ðàçäåëà
	if (edit_data (&$_POST, $section)) header("Location: $location");
	else {
			$title = "Error";
			$error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

	}

	/*	} 	// DEMO VERSION LIMITATIONS */

	break;


// Ïðîñìîòð ýëåìåíòà
case "duplicate":

	$_POST = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");
	
	unset($_POST['id'], $_POST['amount_paid'], $_POST['transaction_id'], $_POST['amountpaid']);

	$_POST['isprojectactive'] = '1';
	$_POST['status_id'] = '2';

	$_POST['deadline'] = date("Y-m-d",time()+(86400*5));

	// Ñîõðàíÿåì äàííûå è âîçâðàùàåìñÿ íà ãëàâíóþ ñòðàíèöó ðàçäåëà
	if (insert_data ($_POST, $section)) {$location =  $siteurl.$section."/edit/".mysql_insert_id(); header("Location: $location");}
	else {
			$title = "Error";
			$error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

	}

	break;

// Ïðîñìîòð ýëåìåíòà
case "closeproject":

	$action = "closeproject";
	$title	= "Closing the project";

	mysql_query("UPDATE `requests` SET `amountpaid` = `estimatedprice`, `status_id` = '10', `isprojectactive` = '0' WHERE `id` = $_GET[id]");

	header("Location: $siteurl{$section}/view/$_GET[id]");

	break;

// Ïðîñìîòð ýëåìåíòà
case "view":

	$action = "view";
	$title = "View";

	$sendemailvisibility = "none";

	// Ïîëó÷àåì ìàññèâ ñ äàííûìè îá îáúåêòå
	$f = RunQueryReturnDataArray($section, "WHERE `id` = $_GET[id]");

	// Åñëè åñòü èíôîðìàöèÿ ïî îáúåêòó ñ òàêèì ÈÄ, òî íà÷èíàåì ðàáîàòü
	if ($f)	{

	// Ìàññèâ ñ äàííûìè î øàáëîíå ñîãëàñíî òåêóùåìó ñòàòóñó
	$template = @RunQueryReturnDataArray ("templates", "WHERE status_id=$f[status_id]");

	// Îòêóäà áåðåì äàííûå î öåíå äëÿ âñòàâêè â øàïêó
	$priceperword = array("1" => "ppwt", "2" => "ppw");

		// Åñëè ñóùåñòâóåò øàáëîí äëÿ òåêóùåãî ñòàòóñà ïðîåêòà
		if ($section == "requests" && $template) {

			$sendemailvisibility = "block";	
			$baseppw = $f[$priceperword[$template['group_id']]];
		
		}

	}

	else { $error_msg = "Object not found. Please <a href='/mad/'><B>return back</B></a> to make a new search"; $section = "default"; $action = "notfound"; }

	break;

// Ñòðàíè÷êà íàïèñàíèÿ ïèñüìà
case "compose":

	global $filelisting;

	// Îòêóäà áåðåì äàííûå î öåíå äëÿ âñòàâêè â øàïêó
	$priceperword = array("1" => "ppwt", "2" => "ppw");

	$action = "compose";
	$title = "Compose email";

	// Íåêîòîðûå áàçîâûå êîíôèãè
	$curr = "-USD";

	// Äàííûå î êîíêðåòíîì ïåðåâîäå
	$f = @RunQueryReturnDataArray ($section, "WHERE id=$_GET[id]");

	// Ïîëó÷àåì ìàññèâ ñ äàííûìè î øàáëîíå äëÿ òåêóùåãî ñòàòóñà ïåðåâîäà
	$template = @RunQueryReturnDataArray ("templates", "WHERE status_id=$f[status_id]");

	$baseppw = $f[$priceperword[$template['group_id']]];

	// Ïîëó÷àåì äàííûå ñ èìåíåì è åìåéëîì ïîëó÷àòåëÿ
	$recepient_details = RunQueryReturnDataArray ($group[$template['group_id']]."s", $more="WHERE id=".$f[$group[$template['group_id']]."_id"], $column="*");

	$baseprice = round($f['wordcount']*$baseppw,2);

	if (!empty($f['currency'])) $curr = strtoupper("-".$f['currency']);
	$baseprice = array($baseprice.$curr, round(($baseprice*1.3),2).$curr, round(($baseprice*1.699),2).$curr);

	// ôîðìèðóåì äàííûå îá îòïðàâèòåëå
	$f['fromname'] = "$UserDetails[firstname] $UserDetails[lastname]";
	$f['fromemail'] = $UserDetails['email'];

	// Äàííûå î ïîëó÷àòåëå
	$f['toname'] = 	"$recepient_details[firstname] $recepient_details[lastname]";
	$f['toemail'] = $recepient_details['email'];

	// Äàííûå î çàãîëîâêå ïèñüìà (ïðåâüþ)
	$f['from'] = $f['fromname']." &lt;".$f['fromemail']."&gt;";
	$f['to'] = $f['toname']." &lt;".$f['toemail']."&gt;";
	
	break;

// Ïðåäâàðèòåëüíûé ïðîñìîòð ïèñüìà
case "preview":

	$action = "preview";
	$title = "Email Preview";

	// Äàííûå î êîíêðåòíîì îáúåêòå(ðàññûëêå, ïåðåâîä÷èêå)
	$f = @RunQueryReturnDataArray ($section, "WHERE id=$_GET[id]");

	@$f['to'] = $_POST['toname']." &lt;".$_POST['toemail']."&gt;";
	@$f['from'] = $_POST['fromname']." &lt;".$_POST['fromemail']."&gt;";

	break;

	// Ïðåäâàðèòåëüíûé ïðîñìîòð ïèñüìà
case "download":

	$action = "download";

		switch($section) {
		
		default: 

			$template = "shipping_label.txt";

			// Äàííûå î êîíêðåòíîì îáúåêòå(ïðîåêòå,ïåðåâîä÷èêå è òä.)
			$f = @RunQueryReturnDataArray ("customers c, requests r", "WHERE r.id='$_GET[id]' AND r.customer_id = c.id","*");

			$filename = $_GET['id'] . "-reamde.txt";

			$f['iscertificationrequired']	= $YesOrNoArray[$f['iscertificationrequired']];
			$f['isnotarizationrequired']	= $YesOrNoArray[$f['isnotarizationrequired']];
			$f['isscanrequired']			= $YesOrNoArray[$f['isscanrequired']];
			$f['isexpressmailrequired']		= $ShippingOptions[$f['isexpressmailrequired']];

			header('Content-Type: Content-Type: text/plain');

			break;

	case "biz":

			global $Settings;

			// Äàííûå î êîíêðåòíîì îáúåêòå(ïðîåêòå,ïåðåâîä÷èêå è òä.)
			$f = RunQueryReturnDataArray ("biz", "WHERE `id`='$_GET[id]'","*");
			
			// Ëîêàëèçàöèÿ äàòû
			$month_ua = array("", "січня", "лютого", "березня", "квітня", "травня", "червня", "липня", "серпня", "вересня", "жовтня", "листопада", "грудня");
			$utime = strtotime($f['dateSigned']);
			$utimeC = strtotime($f['dateCompleted']);

			$f['number'] = $f['siteId'] - 11;
			$f['dateukr'] = date("d", $utime) . " " . $month_ua[date("n", $utime)] . " " . date("Y", $utime);
			$f['datecompletedC'] = date("d", $utimeC) . " " . $month_ua[date("n", $utimeC)] . " " . date("Y", $utimeC);
			$f['dateeng'] = date("F d, Y", strtotime($f['dateSigned']));
			$f['siteIdUkr'] = $f['siteId'];
			$f['siteIdEng'] = $f['siteId'];
			$f['ratePerHour'] = $Settings['defaultPricePerHour'];

			$f['amountWritten'] = num2str($f['amount']);
			$f['hoursWritten'] = num2str($f['numberOfHours']);

			switch($_GET['doc']) {
				
				default:

					$filename = "agreement-$f[dateSigned].doc";
					$template = "agreement-template.rtf";
					break;
	
				case "act":

					$template = "act-template.rtf";
					$filename = "act-$f[dateSigned].doc";
					break;

			}

		    header('Content-Type: application/rtf');

		break;

		}


    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');



	echo ApplyTemplate($template,$f);
	die();

	break;

// Ïðåäâàðèòåëüíûé ïðîñìîòð ïèñüìà
case "dlinvoice":

	$microtime = time();

	// Äàííûå î êîíêðåòíîì îáúåêòå(ïðîåêòå,ïåðåâîä÷èêå è òä.)
	$f = @RunQueryReturnDataArray ("customers c, requests r", "WHERE r.id='$_GET[id]' AND r.customer_id = c.id","*");

	$filename = $_GET['id'] . "-invoice.doc";

	$f['iscertificationrequired']	= $YesOrNoArray[$f['iscertificationrequired']];
	$f['isnotarizationrequired']	= $YesOrNoArray[$f['isnotarizationrequired']];
	$f['isscanrequired']			= $YesOrNoArray[$f['isscanrequired']];
	$f['isexpressmailrequired']		= $ShippingOptions[$f['isexpressmailrequired']];
	$f['wire_transfer_fee']			= $Settings['wire_transfer_fee'];
	$f['currency']					= $Settings['currency'];
	$f['service']					= getNameById($f['area_id'],'rates','name'); 
	$f['discount_amount']			= round($f['estimatedprice'] * ($f['discount']/100),2);
	$f['online_amount']				= $f['estimatedprice']-$f['discount_amount'];
	$f['invoicedate']				= date("F d, Y",$microtime); 
	$f['duedate']					= date("F d, Y",$microtime+86400*10);
	$f['total_due']					= round($f['estimatedprice'] - $f['discount_amount'] - $f['amountpaid']);

    header('Content-Description: File Transfer');
    header('Content-Type: application/rtf');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');

	echo ApplyTemplate("invoice.rtf",$f);
	die();

	break;

// Îòïðàâêà ïèñüìà
case "do_send":

	$action = "view";
	$sendemailvisibility = "none";

	$title = "Status of Request";
	if (sendmail2()) {

		$details = array(
		"baseppw" => $_POST['baseppw'],
		"status_id" => $_POST['status_id']+1,
		"id" => $_GET['id']
		);

		edit_data(&$details, $_GET['section']);
	}

	$title = "View";
	$f = @RunQueryReturnDataArray ($section, "WHERE id=$_GET[id]");

	if ($section == "requests" && $f['estimatedprice']>0 && $f['status_id']==3) $baseppw = $f['ppwt'];
		elseif ($section == "requests" && $f['estimatedprice']>0) $baseppw = round($f['estimatedprice']/$f['wordcount'],4);
		else $baseppw = $calculator['baseppw'];

	break;

// Îòïðàâêà ïèñüìà
case "do_sendnewsletter":

	sendnewsletters();

	$action = "view";
	$f = @RunQueryReturnDataArray ($section, "WHERE id=$_GET[id]");
	$title = "Status of Request";

	break;

// Ñòðàíèöà çàêðûòèÿ âñïëûâàþùåãî îêíà
case "popupclose":

	$action = "popupclose";
	$title = "Graphical View";

	break;

// Îòîáðàæåíèå ñòàòèñòèêè
case "stats":

	$action = "stats";
	$title = "Different stats";

	break;

// AJAX. Èçìåíåíèÿ çíà÷åíèÿ 
case "changevalue":

	$table = $_GET['section'];
	$request = parse_url($_SERVER['REQUEST_URI']);
	parse_str($request['query'], $details);

	if (ChangeValue()) {echo "OK"; exit;}

	break;

// Ñòðàíèöà èçìåíåíèÿ ïàðîëÿ
case "login":

	$action = "login";
	$title = "Password change";

	$f = RunQueryReturnDataArray($section, "WHERE `id` = $_SESSION[staff_id]");

	break;

// Ìîé àêêàóíò
case "mydetails":

	$action = "mydetails";
	$title = "My details";

	$f = RunQueryReturnDataArray($section, "WHERE `id` = $_SESSION[staff_id]");

	break;

// Ñòðàíèöà c áàíêîâñêîé èíôîðìàöèåé
case "bankdetails":

	$action = "bankdetails";
	$title = "Bank details";

	$f = RunQueryReturnDataArray($section, "WHERE `id` = 1");

	break;

// Ôàêòè÷åñêîå óäàëåíèå îáúåêòà
case "delete":

	// DEMO VERSION LIMITATIONS

	$title = "DEMONSTRATION VERSION LIMITATIONS";

	if ($section == "status" || $section == "templates") {

		$error_msg = "Sorry. This feature is disabled in the demo-version";
		$section = "default";
		$action = "demo";

	}

		else

			{

			if (delete_data("id", $section, $_GET['id'])) {

				delete_files($_GET['id']);
				header("Location: {$siteurl}$section/");

			}	
			
			else {

				$title = "Error";
				$error_msg = "Changes where <B>NOT MADE</B>. Please call to support and state the following: " . mysql_errno() . mysql_error();

			}

			}

	break;

// Âûïîëíåíèå çàïðîñà ê áàçå äàííûõ
case "do_runquery":

		$action = "default";
		$error_msg = $translation['137'];

		$title = "Developers corner";

		// Don't change here
		if (mysql_query($_POST['sql'])) return 1; else { $error_msg = $translation['138'] . mysql_errno() . ": " .mysql_error();  return 0; }
		// Don't change here */


	break;

}
?>