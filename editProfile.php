<?php
ob_start();

session_start();

$pageTitle = 'Edit Profile';

include "init.php";


if (isset($_SESSION['uid'])) {
    $userid = isset($_SESSION['uid']) && is_numeric($_SESSION['uid']) ? intval($_SESSION['uid']) : 0;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $formErrors = array();

        $id = $_POST['userid'];
        $user = $_POST['username'];
        $email = $_POST['email'];
        $name = $_POST['full'];

        $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);


        if (strlen($user) < 4) {
            $formErrors[] = 'Username cant be less than <strong>4</strong> characters</div>';
        }

        if (strlen($user) > 20) {
            $formErrors[] = 'Username cant be more than <strong>20</strong> characters';
        }

        if (empty($user)) {
            $formErrors[] = 'Username cant be <strong>empty</strong>';
        }

        if (empty($email)) {
            $formErrors[] = 'Email cant be <strong>empty</strong>';
        }

        if (empty($name)) {
            $formErrors[] = 'Full Name cant be <strong>empty</strong>';
        }

        if (empty($_FILES['avatar']['name'])) {

            if ($_POST['oldavatar'] === NULL || $_POST['oldavatar'] === '') {
                $avatar = NULL;
            }
            else {
                $avatar = $_POST['oldavatar'];

            }
            echo $avatar;
        }
        else {
            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtensions = array("jpeg", "jpg", "png", "gif");
            $ex = explode('.', $avatarName);
            $avatarExtension = strtolower(end($ex));

            if (!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtensions)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }
            if ($avatarSize > 4194304) {
                $formErrors[] = 'Avatar Can\'t Be Larger Than <strong>4MB</strong>';
            }
            if (empty($formErrors)) {

                $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                $stmt2->execute(array($user, $id));
                $count = $stmt2->rowCount();

                if ($count == 1) {
                    $theMsg = '<div class="alert alert-danger">Sorry Username exists</div>';
                    redirectHome($theMsg, 'back');
                }
                else {
                    $avatar = rand(0, 1000000) . '_' . $avatarName;
                    move_uploaded_file($avatarTmp, "../eCommerce/admin/uploads/avatars/" . $avatar);
                }

            }

        }

        foreach ($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }

        if (empty($formErrors)) {

            $stmt2 = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
            $stmt2->execute(array($user, $id));
            $count = $stmt2->rowCount();

            if ($count == 1) {
                $theMsg = '<div class="alert alert-danger">Sorry Username exists</div>';
                redirectHome($theMsg, 'back');
            }
            else {
                $stmt = $con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ?, avatar = ? WHERE UserID = ?');
                $stmt->execute(array($user, $email, $name, $pass, $avatar, $id));

                echo '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
                header("refresh:3,url='logout.php'");
            }
        }
    }

    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
    $stmt->execute(array($userid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    if ($count > 0) {
?>
<h1 class="text-center"><?php echo $pageTitle; ?></h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><?php echo $pageTitle; ?></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="userid" value="<?php echo $userid; ?>">

<div class="form-group form-group-lg">
    <label class="col-sm-3 control-label">Username</label>
    <div class="col-sm-10 col-md-9">
        <input type="text" class="form-control" name="username" value="<?php echo $row['Username']; ?>" auto-complete="off"/>
        
    </div>
</div>

<div class="form-group form-group-lg">
    <label class="col-sm-3 control-label">Password</label>
    <div class="col-sm-10 col-md-9">
        <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>" />   
        <input type="password" class="form-control" name="newpassword" auto-complete="new-password" placeholder="Leave blank if u dont want to change" />
    </div>
</div>

<div class="form-group form-group-lg">
    <label class="col-sm-3 control-label">Email</label>
    <div class="col-sm-10 col-md-9">
        <input type="email" class="form-control" name="email" value="<?php echo $row['Email']; ?>" />
    </div>
</div>

<div class="form-group form-group-lg">
    <label class="col-sm-3 control-label">Full Name</label>
    <div class="col-sm-10 col-md-9">
        <input type="text" class="form-control" name="full" value="<?php echo $row['FullName']; ?>" />
    </div>
</div>

<input type="hidden" name="oldavatar" value="<?php echo $_SESSION['avatar']; ?>">
<div class="form-group form-group-lg">
                    <label class="col-sm-3 control-label">New Avatar</label>
                    <div class="col-sm-10 col-md-9">
                        <input type="file" class="form-control live-avatar" name="avatar" data-class=".live-ava">
                    </div>
                </div>

<div class="form-group form-group-lg">
    <div class="col-sm-offset-3 col-sm-9">
        <input type="submit" class="btn btn-primary btn-lg" value="Save">
    </div>
</div>

                            </form>
                    </div>
                    <?php
    }
    else {
        echo '<div class="container">';
        $theMsg = '<div class="alert alert-danger">Theres no such id</div>';
        redirectHome($theMsg);
        echo '</div>';
    }?>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                            <img class="live-ava" src="img.png" alt="" />
                        </div>
                    </div>
                </div>
<?php
    if (!empty($formErrors)) {
        foreach ($formErrors as $error) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
    }


?>
            </div>
        </div>
    </div>
</div>

<?php

}
else {
    header('Location: login.php');
    exit();
}

include $tpl . 'footer.php';
ob_end_flush();
?>
