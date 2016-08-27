<?php
require_once ("../inc/config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST["name"]);// TRIM eliminates the white spaces in the beginning and the end of the input entered by the user
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    //Check if there are fields left empty
    if($name == "" OR $email == "" OR $message == ""){
        $error_message = "You must specify a value for name, email and a message.";
    }

    if(!isset($error_message)){
        //Check if there was an attack attempt
        foreach ($_POST as $value ){
            if (stripos($value,'Content-Type:') !== FALSE){
                $error_message = "There was a problem with the information you entered.";
            }
        }
    }

    //Check if there was an attack attempt
    if (!isset($error_message) && $_POST["address"] != ""){
        $error_message = "Your form submission has a problem.";
    }
    //Check if the email is valid
    require_once(ROOT_PATH . "inc/phpmailer/class.phpmailer.php");
        $mail = new PHPMailer();
    if (!isset($error_message) && !$mail->validateAddress($email) ){
        $error_message = "You must specify a valid email address.";
    }

    //Deal with the error_message var
    if(!isset($error_message)){
        $email_body = "";
        $email_body = $email_body."Name: " .$name."<br>";
        $email_body = $email_body."Email: ".$email."<br>";
        $email_body = $email_body."Message: ".$message;

        $mail->SetFrom($email, $name);
        $address = "orders@shirts4mike.com";
        $mail->AddAddress($address, "Shirts 4 Mike");
        $mail->Subject    = "Shirts 4 Mike Contact Form Submission | " . $name;
        $mail->MsgHTML($email_body);

        if($mail->Send()) {
            header("Location:". BASE_URL ." contact/?status=thanks");
            exit;
        }else{
            $error_message = "There was a problem sending the email: " . $mail->ErrorInfo;
        }
    }
}
?><?php
$section = "contact";
$pageTitle = "Contact Mike";
include(ROOT_PATH . 'inc/header.php'); ?>

<div class="section page">

    <div class="wrapper">
        <h1>Contact</h1>

    <?php if (isset($_GET["status"]) AND $_GET["status"] == "thanks"){ ?>
        <p>Thanks for the email! I&rsquo;ll be in touch shortly.</p>
    <?php }else{ ?>

        <?php
            if(!isset($error_message)) {
              echo ' <p>I&rsquo;d love to hear from you! Complete the form to send an email.</p>';
            }else{
                echo '<p class="message">'.$error_message.'</p>';
            }
        ?>
        <form method="post" action="<? echo BASE_URL;?>contact/">

            <table>
                <tr>
                    <th>
                        <label for="name">Name</label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" value="<?php if(isset($name)){echo htmlspecialchars($name);}?>" >
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="email">Email</label>
                    </th>
                    <td>
                        <input type="text" name="email" id="email"  value="<?php if(isset($email)){echo htmlspecialchars($email);}?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="message">Message</label>
                    </th>
                    <td>
                        <textarea name="message" id="message"><?php if(isset($message)){echo htmlspecialchars($message);}?></textarea>
                    </td>
                </tr>
                <tr style="display: none;">
                    <th>
                        <label for="address">Address</label>
                    </th>
                    <td>
                        <input  type="text" name="address" id="address">
                        <p>Please leave this field empty.</p>
                    </td>
                </tr>
            </table>
            <input type="submit" value="Send">

        </form>

    <?php } ?>

    </div>

</div>
<?php include(ROOT_PATH . 'inc/footer.php'); ?>