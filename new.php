<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
<?php
use PHPMailer\PHPMailer\PHPMailer;
// Load Composer's autoloader
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';

// STEP 1: read POST data
// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
// Instead, read raw POST data from the input stream.
$raw_post_data = file_get_contents('php://input');

$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2){
    $myPost[$keyval[0]] = urldecode($keyval[1]);
  }
}
// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
$req = 'cmd=_notify-validate';
if (function_exists('get_magic_quotes_gpc')) {
  $get_magic_quotes_exists = true;
}
foreach ($myPost as $key => $value) {
  if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    $value = urlencode(stripslashes($value));
  } else {
    $value = urlencode($value);
  }
  $req .= "&$key=$value";
}

// Step 2: POST IPN data back to PayPal to validate
$ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
// In wamp-like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
// the directory path of the certificate as shown below:
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if ( !($res = curl_exec($ch)) ) {
  // error_log("Got " . curl_error($ch) . " when processing IPN data");
  curl_close($ch);
  exit;
}
curl_close($ch);



$ch1 = curl_init('https://realemail.expeditedaddons.com/?api_key=' . getenv('REALEMAIL_API_KEY') . '&email=email%40example.org&fix_typos=false');

$response = curl_exec($ch1);
curl_close($ch1);

var_dump($response);


// inspect IPN validation result and act accordingly
if (strcmp ($res, "VERIFIED") == 0) {
  
  // The IPN is verified, process it:
  // check whether the payment_status is Completed
  // check that txn_id has not been previously processed
  // check that receiver_email is your Primary PayPal email
  // check that payment_amount/payment_currency are correct
  // process the notification
  // assign posted variables to local variables
  $name = $_POST['first_name'] . " " . $_POST['last_name'];
  $item_name = $_POST['item_name'];
  $item_number = $_POST['item_number'];
  $payment_status = $_POST['payment_status'];
  $payment_amount = $_POST['mc_gross'];
  $payment_currency = $_POST['mc_currency'];
  $txn_id = $_POST['txn_id'];
  $receiver_email = $_POST['receiver_email'];
  $payer_email = $_POST['payer_email'];
  // IPN message values depend upon the type of notification sent.
  
  if ($item_number == "IntroWorkshopFaculty" && $payment_currency == "USD" && $payment_status == "Completed" && $payment_amount == 48.5025){
        $data = "$name\r\n$payer_email\r\n$receiver_email\r\n$item_name\r\n$payment_amount\r\n$payment_currency\r\n$item_number\r\n$payment_status\r\n";
        $mail = new PHPMailer();

        
        $mail->setFrom('solomaboya@gmail.com', 'Thato Maboya');
        $mail->addAddress($payer_email, $name);
        $mail->isHTML(true); 
        $mail->Subject = "Your Purchase Details";
        $mail->Body = "
                   Hi, <br><br/>
                   Thank you for purchase. In the attachment you will find my
                   amzing REDCap Introductory workshop payments details.<br/><br/>

                   Kind regards,
                   Thato Maboya.
        ";
        //$mail->addAttachment('');
        
        $mail->send();
        if(!$mail->send()) {
         file_put_contents("error.txt", $data);
        } else {
           file_put_contents("sent.txt", 'Email was sent');
       }
  }

  


} else if (strcmp ($res, "INVALID") == 0) {
  // IPN invalid, log for manual investigation
  echo "The response from IPN was: <b>" .$res ."</b>";
}

?>
</body>
</html>
