<?php
ob_start();

session_start();
$noNavbar = '';
$pageTitle = 'Login';

if (isset($_SESSION['Username'])) {
    header('Location: dashboard.php');
}
include "init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pass'];
    $hashedpass = sha1($password);

    $stmt = $con->prepare("SELECT UserID,Username,Password FROM users WHERE Username = ? AND Password = ? AND GroupID = 1 LIMIT 1");
    $stmt->execute(array(
        $username,
        $hashedpass
    ));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if ($count > 0) {
        $_SESSION['Username'] = $username;
        $_SESSION['ID'] = $row['UserID'];
        header('Location: dashboard.php');
        exit();
    }

}
?>

   <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
       <h4 class="text-center">Admin Login</h4>
       <input class="form-control" type="text" name="user" placeholder="Username" auto-complete="off" />
       <input class="form-control" type="password" name="pass" placeholder="Password" auto-complete="new-password" />
       <input class="btn btn-primary btn-block" type="submit" value="Login" />

   </form>

<?php include $tpl . 'footer.php';

ob_end_flush();
?>
