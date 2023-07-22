<?php
ob_start();

session_start();
$pageTitle = 'Login';

if (isset($_SESSION['user'])) {
    header('Location: index.php');
}

include 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $hashedpass = sha1($pass);

        $stmt = $con->prepare("SELECT UserID, Username,Password,avatar FROM users WHERE Username = ? AND Password = ?");
        $stmt->execute(array(
            $user,
            $hashedpass
        ));
        $get = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
            $_SESSION['user'] = $user;
            $_SESSION['uid'] = $get['UserID'];
            $_SESSION['avatar'] = $get['avatar'];

            header('Location: index.php');
            exit();
        }
    }
    else {
        $formErrors = array();

        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $email = $_POST['email'];

        if (isset($username)) {
            $filteredUser = filter_var($username, FILTER_SANITIZE_STRING);
            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username Must be more than 4 characters';
            }
        }

        if (isset($password) && isset($password2)) {

            if (empty($password)) {
                $formErrors[] = 'Password Can\'t Be Empty';
            }
            $pass1 = sha1($password);
            $pass2 = sha1($password2);

            if ($pass1 !== $pass2) {
                $formErrors[] = 'Passwords Don\'t Match';
            }

        }
        if (isset($email)) {
            $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'Sorry Email Is Not Valid';
            }
        }

        if (empty($formErrors)) {

            $check = checkItem("Username", "users", $username);
            if ($check == 1) {
                $formErrors[] = 'Sorry This User Exists';
            }
            else {

                $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, RegStatus, Date) VALUES(:zuser,:zpass,:zemail,0,now())");
                $stmt->execute(array(
                    ':zuser' => $username,
                    ':zpass' => sha1($password),
                    ':zemail' => $email,
                ));
                $successMsg = 'Congrats You Are Now Registered';
            }
        }


    }
}
?>

<div class="container login-page">
    <h1 class="text-center">
        <span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span>
    </h1>
    <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
        <div class="input-container">
            <input class="form-control" type="text" name="username" auto-complete="off" placeholder="Type Your Username" required />
        </div>

        <div class="input-container">
        <input class="form-control" type="password" name="password" auto-complete="new-password" placeholder="Type Your Password" required />
        </div>
        
        <div class="input-container">
        <input class="btn btn-primary btn-block" name="login" type="submit" value="Login" />
        </div>
    </form>

    <form class="signup" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
        <div class="input-container">
        <input pattern=".{4,}" title="Username Must Be More Than 4 Chars" class="form-control" type="text" name="username" auto-complete="off" placeholder="Type Your Username" required />
        </div>

        <div class="input-container">
        <input minlength="4" class="form-control" type="password" name="password" auto-complete="new-password" placeholder="Type A Complex Password" required />
        </div>

        <div class="input-container">
        <input minlength="4" class="form-control" type="password" name="password2" auto-complete="new-password" placeholder="Re-Type Your Password" required />
        </div>

        <div class="input-container">
        <input class="form-control" type="email" name="email" placeholder="Type A Valid Email" required />
        </div>

        <div class="input-container">
        <input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp" />
        </div>

    </form>
    <div class="the-errors text-center">
<?php
if (!empty($formErrors)) {
    foreach ($formErrors as $error) {
        echo '<div class="msg error">' . $error . '</div>';
    }
}
if (isset($successMsg)) {
    echo '<div class="msg success">' . $successMsg . '</div>';
}

?>
</div>
</div>

<?php
include $tpl . 'footer.php';
ob_end_flush();

?>