<?

global $translation;

$buildings['k1'] = array(

"Корпус 1	61	9",
"3А	3Б	1А	1Б	1В	2А	3В",
"3А	3Б	1А	1Б	1В	2А	3В",
"3А	3Б	1А	1Б	1В	2А	3В",
"3А	3Б	1А	1Б	1В	2А	3В",
"3А	3Б	1А	1Б	1В	2А	2Б",
"3А	3Б	1А	1Б	1В	2А	2Б",
"3А	3Б	1А	1Б	1В	2А	2Б", // итд
"3А	3Б	1А	1Б	1В	2А	2Б", // 2-й этаж
"3А	3Б	1А	2А	2Б		", // 1-й этаж

);

$buildings['k2'] = array(

"Корпус 2	61	9",
"3А	2A	1А	1Б	1В	3Б	3В",
"3А	2A	1А	1Б	1В	3Б	3В",
"3А	2A	1А	1Б	1В	3Б	3В",
"3А	2A	1А	1Б	1В	3Б	3В",
"2А	2Б	1А	1Б	1В	3А	3Б",
"2А	2Б	1А	1Б	1В	3А	3Б",
"2А	2Б	1А	1Б	1В	3А	3Б", // итд
"2А	2Б	1А	1Б	1В	3А	3Б", // 2-й этаж
"2А	2Б	1А	3А	3Б		", // 1-й этаж

);

$buildings['k3s1'] = array(

"Корпус 3 секция 1	52	15",
"				",
"				",
"			2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"3А	1А	1Б	2А	3Б",
"				",
"				",

);

function generateHouse($building) {

	$buildingProperties = explode("\t",array_shift($building));
	$totalFloors = $buildingProperties['2']+1;
	$totalAptsPerHouse = $buildingProperties['1'];
	
	foreach ($building as $key=>$val) {

		$properties = explode("\t",$val);
		$totalAptsPerFloor = count(array_filter($properties));
		$totalFloors--;

		$line .= "\n<tr><th>$totalFloors</th>\n";

		for ($i=0; $i < $totalAptsPerFloor; $i++) {
			
			$totalAptsPerHouse--;
			$aptNo = $totalAptsPerHouse - $totalAptsPerFloor + $i + $i + 2;
			$line .= "\t<td>$aptNo / $properties[$i]</td>\n";

			unset($aptNo);
		}

		$line .= "</tr>\n";

	}

	return  "<table class='table table-bordered'>$line</table>";

	unset($buildingProperties);

}

?>
<meta charset="utf-8">
<meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">

<? foreach ($buildings as $key => $val) {

	// print_r($key);

	echo generateHouse($buildings[$key]);

}