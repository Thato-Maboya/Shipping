<!DOCTYPE html>
<html lang="en">
<head>
  <title>Email Reset Password</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;





    
    
            $servername = "localhost";
            $usern = "root";
            $passw = "Solomaboya@16650";
            $dbname = "mydatabase";
            // Create connection
            $conn = new mysqli($servername, $usern, $passw, $dbname);
    if(isset($_POST['forgt'])){
        $email = $_POST['email'];
        
        $select = "SELECT * FROM signup WHERE email='$email'";
        $result=mysqli_query($conn, $select);
        $count=  mysqli_num_rows($result);
        $data=  mysqli_fetch_array($result);
        
        $idData=$data['id'];
        $emailData=$data['email'];
        $nameData=$data['firstname'];
        
        // Load Composer's autoloader
        require 'vendor/autoload.php';
        require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require 'vendor/phpmailer/phpmailer/src/Exception.php';
        // Instantiation and passing `true` enables exceptions
        
        if($email==$emailData){
        $mail = new PHPMailer();
        
        //require_once "PHPMailer.php";
        //require_once "Exception.php";
        //require 'PHPMailerAutoload.php';
        
        
                //$mail = new PHPMailer;
                //   // Add a recipient
                $mail->isSMTP(true);                                      // Set mailer to use SMTP
                $mail->SMTPDebug=0;
                $mail->SMTPAuth =true;
                $mail->SMTPSecure='ssl';
                $mail->Host='smtp.gmail.com';
                $mail->Port=465;
                $mail->isHTML(true);                                 // Set email format to HTML
                $mail->Username='solomaboya@gmail.com';
                $mail->Password='nthabisen';
                $address='solomaboya@gmail.com';                     // TCP port to connect to
                $mail->setFrom($address);

                $mail->Subject = 'Reset Password :';
                $mail->Body    = "Hi, Click the link to reset your Password."."<br><a href=http://localhost/dashboard/EmailPassword.php?id=".$idData.">Reset Password</a>";
                //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $mail->addAddress($email); 
                if(!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    $msg='<div class="alert alert-success">Message has been sent</div>';
                }
        }
         else{ 
             $msg='<div class="alert alert-danger">Email does not match in the database</div>'; 
        }  
    }
    
    ?>
    <br><br>

<div class="container">
    <?php
        if (isset($msg)){
            echo $msg;
        }
    ?>
    <?php
        if (isset($errMsg)){
            echo $errMsg;
        }
    ?>
    <form action="" method="post">
        <h1>Enter Your Email to Reset Your Password</h1>
        <hr style="color: lime">
        <div class="col-md-12" style="width: 50%">
            <label>Email</label><br>
            <input type="email" name="email" placeholder="Enter Email" size="50%" required="">
            <br>         
            <input class="btn btn-success " type="submit" name="forgt" value="submit" style="height:5%">
        </div>
        
    </form>
</div>

</body>
</html>



