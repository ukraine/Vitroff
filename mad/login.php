<?

global $link, $url, $siteurl, $loggedin;
$error_msg = "Please login.";

if (isset($_SERVER['HTTP_REFERER'])) $url = $_SERVER['HTTP_REFERER'];

function sendNewPass() {

	global $error_msg, $Settings;

	$_POST['id'] = 1;
	$section = "settings";
	$error_msg = "Введенное имя пользователя не совпадает. Если вы ошиблись, то воспользуйтесь формой напоминания пароля снова.";
	$subject = "Восстановление пароля";

	if ($_POST['login'] == $Settings['login']) {
		$NewPass = rand();
		include "lib/default.func.php";
		$_POST['password'] = md5($NewPass);
		unset($_REQUEST['phpbb2mysql_data']);
		unset($_REQUEST['PHPSESSID']);
		unset($_REQUEST['submit']);
		unset($_REQUEST['login']);
		unset($_REQUEST['action']);
		$_REQUEST['Ваш новый пароль'] = $NewPass;
		if (edit_data (&$_POST, $section)) {
			sendmail(&$subject);
			$error_msg = "Пароль выслан на <B>$_POST[login]</B>";
		} else $error_msg = "Ошибка отправки. Попытайтесь попозже или, если в течение продолжительного времени вы не получили письма, свяжитесь с поддержкой";

	}


}


function openCloseAccess() {

	global $url, $error_msg, $link, $db, $form, $error_msg;

	$error_msg = "<span id='red'>Login incorrect. Please try again. </span>";" // логин или пароль. Напрягитесь, вспомните верные данные и попробуйте снова</span>";

	// Start of switch
	switch($_REQUEST['do']) {

	default:

		break;
		
	case "allow":

		$UserDataAet = mysql_query("SELECT * FROM `staff` WHERE `login` = \"$_POST[login]\" AND `password` = \"".md5($_POST['password'])."\"");
		$UserDetails = mysql_fetch_array($UserDataAet);

			if (mysql_num_rows($UserDataAet)>0) {

				$_SESSION['loggedin'] = "yes";
				$_SESSION['staff_id'] = $UserDetails['id'];
				header("Location: $url");
			}

		break;

	case "logoff":	

			session_start();
			session_destroy();
			header("Location: $url");

		break;
	
	}
	// End of switch
}


if (!empty($_REQUEST['do'])) openCloseAccess();
if (!empty($_REQUEST['action'])) $error_msg = "Password resetting is disabled due to the demo-version limitations"; // Восстановление отключено в демо-версии"; // sendNewPass();

?>
<html>
	<head>
	<title>System login</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" href="<? echo $siteurl; ?>css/mad.css" type="text/css">
	<SCRIPT type="text/javascript" language="JavaScript"> 
		function toggle_visibility(id) {
		var e = document.getElementById(id);
		if(e != null) {
		if(e.style.display == 'none') {
		e.style.display = 'block';
		} else {
		e.style.display = 'none';
		}
		}
	}
	</script>
	</head>
<body id="login">
<H3 id="first"><? echo $error_msg; ?></H3>
<form action="" name="getAccess" method="post">
	<input type='text' name='login' style='width: 200px' id="login"> <label for="login">username</label> <br>
	<input type="password" name="password" style="width: 200px" id="password"> <label for="password">password </label><br><br>
	<input type="submit" name="access" value="Enter" style='width: 200px'>
	<input type="hidden" name="do" value="allow">
</form>

<span onClick="toggle_visibility('reminder')" class="javascriptlink" style="margin-left: 31px;">I forgot my password</span>
<br><br>

<form action="" name="Reminder" method="post" id="reminder" style="display:none;">
	<input type='text' name='login' style='width: 200px' id="loginf"> <label for="loginf">Enter your username</label><br>
	<input type="submit" name="submit" value="Send me new password" style='width: 200px'>
	<input type="hidden" name="action" value="forgot">
</form>
</body>
</html>