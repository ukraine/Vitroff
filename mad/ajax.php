<?

include "../lib/shared.php";
include "../lib/default.func.php";
include "lib/lib.php";

switch(@$_GET['action']) {

default:

	break;

case "unlinkfile":

	global $filestoragepath;

	// Удаляем файл физически с диска
	unlink($filestoragepath.$_GET['filename']);

	// Удаляем с базы данных
	delete_data ("name", "files", $_GET['filename']);

	// Конец работы
	break;

case "createfile":

	SaveSourceIntofile($_GET['id']);

	break;

}

?>