<?

$status = false;

function sendmail()	{

		foreach($_POST as $key=>$val)
			{
				$str.= "$key : $val\n";
			}
		if(mail("yuriy.yatsiv@gmail.com, brand@soapshop.com.ua", $_POST['subject'], $str))
			{
				return true;
			}
		else
				return false;
}

if (sendmail()) {
	
	$status = true;

}

echo "<status>$status</status>";

?>