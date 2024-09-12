<?php
  require('includes/application_top.php');
### get the self urls for configuration and callback
$url = "http" . (($_SERVER['SERVER_PORT']==443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$homeURL = urlencode($url.'?userid=1234');
$lookupURL = urlencode($url.'?userid=1234&ref=CODE&format=FORMAT');
### get parameters, init in case no record specified
### will escape strings for SQL
$userid = "";
$ref = "";
$format = "";
$gps = "";
$title = "";
$note = "";
$date = "";
$imageType= "";
$imageData= "";
$checkbox = 0;
$message = "";
### GET request: home page or barcode lookup
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
if (isset($_GET['ref'])) {
$ref = $_GET['ref'];
if (isset($_GET['format'])) { $format = $_GET['format']; }
if (isset($_GET['gps'])) { $gps = $_GET['gps']; }
/*$query = "SELECT title, note, date, info, imageType, imageData, checkbox FROM Demo " ."WHERE ref='".$ref."'";
$result = $database->query($query);
$record = $result->fetchArray(SQLITE3_ASSOC);
if (! $record) {
### create record
$query = "INSERT INTO Demo (ref, format, gps, title, note, date, info, imageType, imageData, checkbox) VALUES ('$ref', '$format', '$gps', '$title', '$note', '$date', '$info', '$imageType' , '$imageData', '$checkbox')";
if (! $database->query($query)) {
$message = 'Error: record not created';
}
}
*/
}
}
tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT,'oID='.$format.'&action=barcode_auto&barcode='.$ref));

### POST request: form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
# get all form data
$userid = $_POST['userid'];
$ref = $_POST['ref'];
$format = $_POST['format'];
$gps = $_POST['gps'];
$title = $_POST['title'];
$note = $_POST['note'];
$date = $_POST['date'];
if (isset($_POST['checkbox'])) {
$checkbox = 1;
}
else {
$checkbox = 0;
}
if (isset($_FILES['image']['tmp_name'])) {
if (strlen($_FILES['image']['tmp_name'])>0) {
$imageType = $_FILES['image']['type'];
$imageData = file_get_contents($_FILES['image']['tmp_name']);
# limit image size to 400px
list($width, $height) = getimagesize($_FILES['image']['tmp_name']);
if (max($width, $height)>400) {
$scale = 400.0/max($width, $height);
$new_width = $width * $scale;
$new_height = $height * $scale;
$image = imagecreatefromstring($imageData);
$image_scaled = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($image_scaled, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
ob_start();
imagepng($image_scaled);
$imageData = ob_get_contents();
ob_end_clean();
$imageType = "image/png";
imagedestroy($image);
imagedestroy($image_scaled);
echo 'resized by '.$scale;
}
$imageData = base64_encode($imageData); // mysql_real_escape_string($imageData);
}
}
# update record
//$query = "SELECT title, note, date, info, imageType, imageData, checkbox FROM Demo " .
"WHERE ref='".$ref."'";
$result = $database->query($query);
$record = $result->fetchArray(SQLITE3_ASSOC);
/*if ($record) {
# update everything but image
$query = "UPDATE Demo SET format='$format', gps='$gps', title='$title', note='$note', date='$date', info='$info', checkbox='$checkbox' WHERE ref='$ref'";
	if (! $database->query($query)) {
	$message = 'Error: record not updated';
	}
# update image if provided
	if (strlen($imageData)>0) {
	$query = "UPDATE Demo SET imageType='$imageType' , imageData='$imageData' WHERE ref='$ref'";
			if (! $database->query($query)) {
			$message = 'Error: image not updated';
			}
	}
} else {
# create record
$query = "INSERT INTO Demo (ref, format, gps, title, note, date, info, imageType, imageData, checkbox) VALUES ('$ref', '$format', '$gps', '$title', '$note', '$date', '$info', '$imageType' , '$imageData', '$checkbox')";
	if (! $database->query($query)) {
	$message = 'Error: record not created';
	}
}
*/
}
### get updated record data for display
/*if (strlen($ref)>0) {
$query = "SELECT title, note, date, info, imageType, imageData, checkbox FROM Demo " .
"WHERE ref='".$ref."'";
$result = $database->query($query);
$record = $result->fetchArray(SQLITE3_ASSOC);
if ($record) {
$title = $record['title'];
$note = $record['note'];
$date = $record['date'];
$info = $record['info'];
$imageType = $record['imageType'];
$imageData = $record['imageData'];
$checkbox = $record['checkbox'];
}
} */
### escape strings for display
$title = htmlspecialchars($title);
$note = htmlspecialchars($note);
$date = htmlspecialchars($date);
$info = htmlspecialchars($info);
$imageType = htmlspecialchars($imageType);
$checkbox = htmlspecialchars($checkbox);
  function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&') {

    if (!is_array($exclude)) $exclude = array();



    $get_string = '';

    if (sizeof($array) > 0) {

      while (list($key, $value) = each($array)) {

        if ( (!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y') ) {

          $get_string .= $key . $equals . $value . $separator;

        }

      }

      $remove_chars = strlen($separator);

      $get_string = substr($get_string, 0, -$remove_chars);

    }



    return $get_string;

  }
          if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), 'SSL');
          $navigation->clear_snapshot();
          //tep_redirect($origin_href);
        }
	  echo $origin_href;	
?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

