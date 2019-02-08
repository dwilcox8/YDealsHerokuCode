<?php

$counter = 0;
$url = 'http://yuldeals.com/atom/1';
date_default_timezone_set('America/Denver');

//while($counter < 2){
	$feed = simplexml_load_file($url) or die("feed not working");
	$updated = $feed->entry[0]->updated;
	$title = $feed->entry[0]->title;

	$now = date("Y-m-d\TH:i:s-07:00");

	if ($now < $updated) {
		echo "<br>" . "New Deal!";
		/*echo "<br>" . $updated;
		echo "<br>" . $now;
		echo "<br>" . $title;*/
	} else {
		echo "<br>" . "Not a new deal";
		/*echo "<br>" . $updated;
		echo "<br>" . $now;
		echo "<br>" . $title;*/
	}

//	$counter++;

//}

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"f__Jz8D8E5M:APA91bHLuch30VnhPzMOLYE8H9GnT61n3CtDQQTuTkcObzXvhMfPHG4JNoDXg4P9BWOGbUlr3WnEgZ_ao_0igUJ2nSN6Fv8HaRTGdQ-YteJxtbIWV17-qSZYTVOUBLUVTvWATgxTBiB6\",\r\n \"notification\" : {\r\n     \"body\" : \"$title\",\r\n     \"title\": \"New Travel Deal Available\"\r\n }\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Authorization: key=AIzaSyAuF0x_jr9nk_01x5zComJMHi_bwj7nFSk",
    "Cache-Control: no-cache",
    "Content-Type: application/json",
    "Postman-Token: c37c7542-5519-42d8-9fa1-f24ee6900fa5"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}


?>
