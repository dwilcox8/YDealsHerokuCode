<?php

function sendNotification($title, $token)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\r\n \"to\" : \"$token\",\r\n \"notification\" : {\r\n     \"body\" : \"$title\",\r\n     \"title\": \"New Travel Deal Available\"\r\n }\r\n}",
    CURLOPT_HTTPHEADER => array(
      "Authorization: key=AIzaSyC5BtBwngkWfujJPxPoZd3Rt-lnZCPZrnE",
      "Content-Type: application/json",
    ),
  ));

//"Authorization: key=AIzaSyAuF0x_jr9nk_01x5zComJMHi_bwj7nFSk",
  # code...

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    //echo $response;
  }
}

function sendToDevices($title, $city)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://firestore.googleapis.com/v1/projects/ydeals-98439/databases/%28default%29/documents/" . $city . "/?key={YOUR_API_KEY}",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);

  curl_close($curl);

  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    $obj = json_decode($response);

    foreach ($obj->documents as $document) {
      $token = $document->fields->token->stringValue;
      sendNotification($title, $token);
    }
  }


}

function getDeals()
{
  $counter = 0;

  $urls = array(
    array("https://ydeals.com/atom/1", "Canada Wide"),
    array("https://yycdeals.com/atom/1", "Calgary"),
    array("https://yegdeals.com/atom/1", "Edmonton"),
    array("https://yhzdeals.com/atom/1", "Halifax"),
    array("https://ylwdeals.com/atom/1", "Kelowna"),
    array("https://yxudeals.com/atom/1", "London"),
    array("https://yuldeals.com/atom/1", "Montreal"),
    array("https://yowdeals.com/atom/1", "Ottawa"),
    array("https://yqrdeals.com/atom/1", "Regina"),
    array("https://yxedeals.com/atom/1", "Saskatoon"),
    array("https://yytdeals.com/atom/1", "St. John's"),
    array("https://yqtdeals.com/atom/1", "Thunder Bay"),
    array("https://yyzdeals.com/atom/1", "Toronto"),
    array("https://yvrdeals.com/atom/1", "Vancouver"),
    array("https://ywgdeals.com/atom/1", "Winnipeg")
  );

  date_default_timezone_set('America/Vancouver');


  for ($i=0; $i < count($urls); $i++) { 
    $feed = simplexml_load_file($urls[$i][0]) or die("feed not working");
    $updated = $feed->entry[0]->updated;
    $title = $feed->entry[0]->title;
    $city = $urls[$i][1];

    $now = date("Y-m-d\TH:i:s-07:00");

    if ($now <= $updated) {
      sendToDevices($title, $city);
    } else {
      //do nothing
    }
  }
}


getDeals();

//echo getcwd();

?>
