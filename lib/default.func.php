<?

// ������� ������
function insert_data ($details, $table, $keys="", $values="") {

	global $ForbiddenChars, $AllowedChars;

	foreach($details as $key=>$val)

		{
			if 
			
			(
				   $key !== "section" 
				&& $key !== "action" 
				&& $key !== "submit" 
				&& $key !== "Submit" 
				&& $key !== "dosometh" 
				&& $key !== "id" 
				&& $key !== "months" 
				&& $key !== "years" 
				&& $key !== "timestamp"
				&& $key !== "file"			
			) 
					
			{
				$keys .="`$key`,"; $values .= "'".trim(str_replace($ForbiddenChars, $AllowedChars, $val))."',";
			}
		}

	$strlenkey = strlen($keys);
	$keys = substr($keys, 0, $strlenkey-1);

	$strlenval = strlen($values);
	$values = substr($values, 0, $strlenval-1);

	$sql = "INSERT INTO `$table` ($keys) VALUES ($values)";

	// echo $sql."<BR>";

	// Don't change here
	if (mysql_query($sql)) return 1; else return 0;
	// Don't change here */
	
}


// �������������� ������
function edit_data ($details, $table) {

	global $sqlset, $ForbiddenChars, $AllowedChars;

	foreach($details as $key=>$val)

			{
				if

				(
					$key !== "section" 
					&& $key !== "action" 
					&& $key !== "submit" 
					&& $key !== "Submit" 
					&& $key !== "dosometh" 
					&& $key !== "id" 
					&& $key !== "months" 
					&& $key !== "years" 
					&& $key !== "sid" 
					&& $key !== "file"
				)

				$sqlset .="`$key` = '".trim(str_replace($ForbiddenChars, $AllowedChars, $val))."',";
			}

	$strlenset = strlen($sqlset);
	$sqlset = substr($sqlset, 0, $strlenset-1);

		$sql	=	"
					
					
					UPDATE `$table` SET 
					$sqlset
					WHERE `id` ='$details[id]'
				
					";

	// echo $sql."<BR>";

	// Don't change here
	if (mysql_query($sql)) return 1; else return 0;
	// Don't change here

}

// �������� ������
function delete_data ($what, $where, $id) {

	$sql	= "DELETE FROM `$where` WHERE `$what` = '$id'";

	// Don't change here
	if (mysql_query($sql)) return 1; else return 0;
	// Don't change here

}

?>