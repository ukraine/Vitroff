<?

error_reporting(E_ALL);

if (empty($section)) $section = "default"; else $section = $_REQUEST['section'];
if (empty($action)) $action = "default"; else $action = $_REQUEST['action'];
if (empty($error_msg)) $error_msg = "";
if (!empty($_POST['action'])) $action = $_POST['action'];

// ѕолучаем базовые параметры системы
getSettings();

?>