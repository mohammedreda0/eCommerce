<?php

ob_start();

session_start();

$pageTitle = 'Contact Us';

include "init.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $contactUser  = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $cell  = filter_var($_POST['cellphone'], FILTER_SANITIZE_NUMBER_INT);
        $msg   = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
        
        $formErrors = array();
        if (strlen($contactUser) < 3 ){
            $formErrors[] = 'Username Must Be Greater Than <strong>3</strong> Characters';
        }

        if (strlen($msg) < 10 ){
            $formErrors[] = 'Message Can\'t Be less Than <strong>10</strong> Characters';
        }

        $headers = 'From:' . $email . '\r\n';
        $myEmail = 'mohammedreda094@gmail.com';
        $subject = 'Contact Form';

        if(empty($formErrors)){
            mail($myEmail , $subject , $msg , $headers);

            $contactUser  = '';
            $email = '';
            $cell  = '';
            $msg   = '';

            $success = '<div class="alert alert-success">We Have Recived Your Message</div>';

        }
    }


?>


    <div class="container">
        <h1 class="text-center">Contact Us</h1>
        
        <form class="contact-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
        <?php
                if (!empty($formErrors)){
        ?>
        <div class="alert alert-danger alert-dismissible" role="start">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <?php
                    foreach ($formErrors as $error){
                        echo $error . '<br>';
                    }
            ?>
            </div>
            <?php
                }
            ?>
            <?php
                if(isset($success)){ echo $success; }
            ?>
            <div class="form-group">
                <input class="username form-control" type="text" name="username" placeholder="Type your name" value="<?php if(isset($contactUser)){ echo $contactUser;} ?>">
                <i class="fa fa-user fw"></i>
                <span class="asterisx">*</span>
                <div class="alert alert-danger custom-alert">
                    Username Must Be Greater Than <strong>3</strong> Characters
                </div>
            </div>

            <div class="form-group">
                <input class="email form-control" type="email" name="email" placeholder="Type your email" value="<?php if(isset($email)){ echo $email;} ?>">
                <i class="fa fa-envelope fw"></i>
                <span class="asterisx">*</span>
                <div class="alert alert-danger custom-alert">
                    Email Can't Be <strong>Empty</strong>
                </div>
            </div>

            <input class="form-control" type="text" name="cellphone" placeholder="Type your phone" value="<?php if(isset($cell)){ echo $cell;} ?>">
            <i class="fa fa-phone fw"></i>

            <div class="form-group">
                <textarea class="message form-control" name="message" cols="30" rows="10" placeholder="Youe Message!"><?php if(isset($msg)){ echo $msg;} ?></textarea>
                <span class="asterisx">*</span>
                <div class="alert alert-danger custom-alert">
                    Message Can't Be less Than <strong>10</strong> Characters
                </div>
            </div>

            <input type="submit" class="btn btn-success" value="Send Message">
            <i class="fa fa-paper-plane fw send-icon"></i>

        </form>
    </div>
    <?php
    include $tpl . 'footer.php';
ob_end_flush();
?>