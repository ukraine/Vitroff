<?

$data = "

Birth Certificate
Marriage Certificate
Death Certificate

";


foreach(explode("\n", trim($data)) as $key=>$val) { $i++;  echo "<li><a href='generateDocument$i.php'>$val</a></li>";}

?>