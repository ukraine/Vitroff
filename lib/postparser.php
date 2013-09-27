<?

function PostParser() {

	global $action, $error_msg, $Settings; 

	$do = $_POST['dosometh'];

	switch($do) {

	default:

		break;

	// Первый шаг: рассчет стоимости перевода
	case "step1":

		global $ToEstimateRequiredFields, $wordcount;

		// Страница для отображения результата рассчета
		$action = "step1";

		// Подготовка. Проверка требуемых полей
		if (!IsRequiredFieldsFilled(&$ToEstimateRequiredFields)) { $action = "default"; break; }
		
		// В случае того, если все поля заполнены, подключаем калькулятор и делаем рассчеты
		else include "lib/calculator.php";

		break;

	// Отображение формы отправки заказа и получение дополнительных сведений о заказчике
	case "step2":

		$action = "step2";
		break;


	// Отправка заказа
	case "step3":

		global $RequestRequiredFields;
		include "lib/default.func.php";

		// I. Проверка требуемых полей
		if (!IsRequiredFieldsFilled($RequestRequiredFields)) { $action = "step2"; break; }
	
		// II. Регистрация клиента:
		// Если пользователя нет среди списка клиентов (делаем опрос БД), то:
		if (!ProcessSQL ('customers', "WHERE email = '$_POST[toemail]'")) {

			$table = "customers";
			
			// а. Подготавливаем данные для новой учетной записи	
			$details['ip'] = $_SERVER['REMOTE_ADDR'];
			$details['email'] = trim($_POST['toemail']);

			// Получаем имя и фамилию клиента
			$name = explode(" ", trim($_POST['toname']));
			$details['firstname']	= ucfirst($name['0']);
			$details['lastname']	= ucfirst($name['1']);

			// Дата становления клиентом 
			$details['timestamp'] = date('Y-m-d hh:mm:ss');

			// б. Заносим данные о клиенте в базу данных
			insert_data (&$details, &$table);
		
		}
		
		// III. Занесение клиентского запроса в базу данных
		$table = "requests";
		
		// Присваиваем переменной details все содержимое POST 
		$details = $_POST;

		// Получаем его идентификатор, если он таки существует, для вставки в таблицу запросов
		$details['customer_id'] = ProcessSQL ('customers', "WHERE email = '$_POST[toemail]'");

		// Получаем выбранную сумму и валюту, если они есть
		$estimatedAmount = explode(" ", trim($_POST['estimatedprice']));
		$details['estimatedprice']	= $estimatedAmount['0'];
		$details['currency']		= $estimatedAmount['1'];
		$details['status_id']		= 1;

		// Если не указано конечного срока исполнения, то ставим свою дату
		if (empty($details['deadline'])) {
			$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+5, date("Y"));
			$details['deadline'] = date('Y-m-d',$tomorrow);
		}

		// Убираем лишние данные для последующей вставки в БД
		unset($details['toname'], $details['subject'], $details['ip'], $details['toemail'], $details['email'], $details['firstname'], $details['lastname']);

		// Делаем вставку запроса в таблицу requests
		insert_data (&$details, &$table);

		// IV. Отправляем извещение клиенту
		// Получение данных для подтверждения получения
		$template = mysql_fetch_array(mysql_query("SELECT * FROM templates WHERE id = '5'"));

			$_POST['fromname']	= $Settings['company_name'];
			$_POST['fromemail']	= $Settings['email']; 
			eval("\$_POST[content] = \"$template[content]\";");
			$_POST['subject']	= $template['subject'];

		sendmail();

		// Отправляем извещения админам
		// sendmail();

		$action = "null";
		$error_msg = "Your request was successfully submitted to our staff. Please allow us up to 24 hours for a reply.";

		break;

	}

}
?>