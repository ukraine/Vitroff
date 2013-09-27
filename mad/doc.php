<?

require_once("obninsk_doc.php");
$filename="readme.doc"; 
$s=""; 
$fp = fopen($filename,'rb'); if(!$fp) die("file \"$filename\" not found!");
while (($fp != false) && !feof($fp)) 
$s.=fread($fp,filesize($filename)); 
fclose($fp);
$text_with_html=obninsk_doc($s); 
echo "The file $filename contain a text: ".$text_with_html; 


?>