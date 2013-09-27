<?php

function curl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
#	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_exec($ch);
	$time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
	curl_close($ch);

	return $time;
}

$urls = array
(
	'http://www.starsightings.com/',
	'http://www.starsightings.com/startype-Celebrities/',
	'http://www.starsightings.com/startype-Politicians/',
	'http://www.starsightings.com/filter-week/',
	'http://www.starsightings.com/filter-month/',
	'http://www.starsightings.com/filter-year/',
	'http://www.starsightings.com/filter-alltime/',
	'http://www.starsightings.com/search/advanced-1',
);

for ($i=0, $c=count($urls); $i<$c; $i++)
{
	echo '<p>['.curl($urls[$i]).'] '.$urls[$i].'</p>';
	flush();
}

?>