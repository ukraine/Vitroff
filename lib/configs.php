<?

/*

Ìåãàôîí Ìîñêâà-926, 925
Ìåãàôîí Öåíòð-920
Ìåãàôîí Ñåâåðî Çàïàä(Ïåòåðáóðã)-921
Ìåãàôîí Óðàë-922
Ìåãàôîí Ñèáèðü-923
Ìåãàôîí Äàëüíèé Âîñòîê-924
Ìåãàôîí Ïîâîëæüå-927, 937, 922
Ìåãàôîí Êàâêàç-928
Ïðîñòî äëÿ îáùåíèÿ-929
Ìåãàôîí Þã-920
Ìåãàôîí Òâåðü-920
Àëëî Èíêîãíèòî-499, 926
Ìàòðèõ-926
Ñêàéëèíê-901,495
Áèëàéí-903,905,906,909,960,961,962,963,964,965,967
ÌÒÑ-910,911,912,913,914,915,916,917,918,919,980,985,987,988,981,495
Òåëå2-904,908,951,950,952
ÍÑÑ-952 

*/

global $siteurl, $ForbiddenChars, $AllowedChars, $RequestRequiredFields, $calculator, $filestoragepath;

$connection = mysql_connect("127.0.0.1", 
                            "trof", 
                            "6iB3PJ6hkCJS5winYtQiQ");
mysql_select_db("trof", $connection);
mysql_query("SET NAMES utf8"); 

mysql_query ("set character_set_client='utf8'");
mysql_query ("set character_set_results='utf8'");
mysql_query ("set collation_connection='utf8_general_ci'");

$ForbiddenChars = array("'", "”", "“", "’", "‘ ", "`", "'",">","<","\"");
$AllowedChars = array("&#39;", "&rdquo;", "&ldquo;", "&rsquo;", "&lsquo;", "&#96;", "&#39;","&gt;","&lt;","&quot;");

$YesOrNoArray = array("No","Yes");

$ShippingOptions = array("USPS First Class","USPS Overnight");

// Îáÿçàòåëüíûå ê çàïîëíåíèþ ïîëÿ äëÿ çàïðîñîâ íà ïåðåâîä
$RequestRequiredFields = array("toemail"=>"E-mail address", "toname"=>"Contact name", "source_text"=>"Source text");

// Îáÿçàòåëüíûå ïîëÿ äëÿ êàëüêóëÿòîðà
$ToEstimateRequiredFields = array("source_text"=>"Source text");

// Ïåðåìåííûå êàëüêóëÿòîðà
$calculator	= array("maxwordsperday"=>1000, "baseppw"=>0.05);

// Ìåñòî õðàíåíèÿ ôàéëîâ
$filestoragepath = $_SERVER['DOCUMENT_ROOT']. "/shared/";

// Ïàïêà äëÿ âðåìåííûõ ôàéëîâ
$tempfilestoragepath = $_SERVER['DOCUMENT_ROOT']. "/temp/";

$SMSPortals = array(

"mts"=>"http://www.mts.ru/messaging1/sendsms/?",
"beeline"=>"http://www.beeline.ru/sms/index.wbp?",
"beeline_ua"=>"http://www.kyivstar.ua/sms/?",
"megafon"=>"http://msk.sendsms.megafon.ru/?",
"mtsu"=>"http://www.mts.com.ua/ukr/sendsms.php?",
"kyivstar"=>"http://www.kyivstar.ua/sms/?",
"life"=>"https://www.life.com.ua/sms/smsFree.html?locale=ua&",
"tele2spb"=>"http://www.spb.tele2.ru/send_sms.html",

);

$trackingUtils = array(
"usps" => "http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=",
);


$viewPaymentIds = array(

	"ppl" => "https://www.paypal.com/us/cgi-bin/webscr?cmd=_view-a-trans&id=94L16857HA288254W",

);

// http://mtt.ru/info/def/index.wbp?def=964&number=326&region=&standard=&date=&operator=

?>