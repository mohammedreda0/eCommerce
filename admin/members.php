<?php

ob_start();
session_start();


$pageTitle = 'Members';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $query = '';
        if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
            $query = 'AND RegStatus = 0';
        }

        $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if (!empty($rows)) {
?>
        <h1 class="text-center">Manage Member</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Register Date</td>
                        <td>Control</td>
                    </tr>

                    <?php

            foreach ($rows as $row) {
                echo '<tr>';
                echo '<td>' . $row['UserID'] . '</td>';
                echo '<td>';
                if (empty($row['avatar'])) {
                    echo 'No Image';
                }
                else {
                    echo '<img src="uploads/avatars/' . $row['avatar'] . '"alt="" />';
                }
                echo '</td>';
                echo '<td>' . $row['Username'] . '</td>';
                echo '<td>' . $row['Email'] . '</td>';
                echo '<td>' . $row['FullName'] . '</td>';
                echo '<td>' . $row['Date'] . '</td>';
                echo '<td>
                    <a href="members.php?do=Edit&userid=' . $row['UserID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="members.php?do=Delete&userid=' . $row['UserID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
                if ($row['RegStatus'] == 0) {
                    echo '<a href="members.php?do=Activate&userid=' . $row['UserID'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Activate</a>';
                }

                echo '</td>';
                echo '</tr>';
            }

?>
                    
                </table>
            </div>
            <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new member</a>
        </div>
        <?php
        }
        else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Record To Show</div>';
            echo '<a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new member</a>';
            echo '</div>';
        }?>
        <?php

    }
    elseif ($do == 'Add') { ?>

            <h1 class="text-center">Add New Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username"  auto-complete="off" required="required" placeholder="Username you use to login">
                            
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="password" class="password form-control" name="password" auto-complete="new-password" required="required" placeholder="Password must be hard and complex">
                            <i class="show-pass fa fa-eye fa-2x"></i>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" name="email" required="required" placeholder="Email Must be valid">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="full" required="required" placeholder="Full Name appear in your profile page">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">User Avatar</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="file" class="form-control" name="avatar" required="required">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Member">
                        </div>
                    </div>
                </form>

            </div>

        <?php
    }
    elseif ($do == 'Insert') {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Insert Member</h1>';
            echo '<div class="container">';

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtensions = array("jpeg", "jpg", "png", "gif");
            $ex = explode('.', $avatarName);
            $avatarExtension = strtolower(end($ex));


            $user = $_POST['username'];
            $pass = $_POST['password'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $hashPass = sha1($pass);

            $formErrors = array();

            if (strlen($user) < 4) {
                $formErrors[] = 'Username cant be less than <strong>4</strong> characters';
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
            if (empty($pass)) {
                $formErrors[] = 'Password cant be <strong>empty</strong>';
            }
            if (empty($name)) {
                $formErrors[] = 'Full Name cant be <strong>empty</strong>';
            }
            if (!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtensions)) {
                $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
            }
            if (empty($avatarName)) {
                $formErrors[] = 'Avatar Is <strong>Required</strong>';
            }
            if ($avatarSize > 4194304) {
                $formErrors[] = 'Avatar Can\'t Be Larger Than <strong>4MB</strong>';
            }
            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formErrors)) {

                $avatar = rand(0, 1000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "uploads/avatars/" . $avatar);

                $check = checkItem("Username", "users", $user);
                if ($check == 1) {
                    $theMsg = '<div class="alert alert-danger">Username exists</div>';
                    redirectHome($theMsg, 'back');
                }
                else {
                    $stmt = $con->prepare("INSERT INTO users(Username, Password, Email, FullName, RegStatus, Date, avatar) VALUES(:zuser,:zpass,:zemail,:zfull,1,now(),:zavatar)");
                    $stmt->execute(array(
                        ':zuser' => $user,
                        ':zpass' => $hashPass,
                        ':zemail' => $email,
                        ':zfull' => $name,
                        ':zavatar' => $avatar
                    ));
                    echo '<div class="container">';
                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted</div>';
                    redirectHome($theMsg, 'back');
                    echo '</div>';
                }

            }

        }
        else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';
            redirectHome($theMsg);
            echo '</div>';

        }
        echo '</div>';


    }
    elseif ($do == 'Edit') {

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Page</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid; ?>">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="username" value="<?php echo $row['Username']; ?>" auto-complete="off" required="required" />
                            
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']; ?>" />   
                            <input type="password" class="form-control" name="newpassword" auto-complete="new-password" placeholder="Leave blank if u dont want to change" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" class="form-control" name="email" value="<?php echo $row['Email']; ?>" required="required" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Full Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="full" value="<?php echo $row['FullName']; ?>" required="required" />
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
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
        }
    }
    elseif ($do == 'Update') {
        echo '<h1 class="text-center">Update Member</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['userid'];
            $user = $_POST['username'];
            $email = $_POST['email'];
            $name = $_POST['full'];

            $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

            $formErrors = array();

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
                    $stmt = $con->prepare('UPDATE users SET Username = ?, Email = ?, FullName = ?, Password = ? WHERE UserID = ?');
                    $stmt->execute(array($user, $email, $name, $pass, $id));

                    $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
                    redirectHome($theMsg, 'back');
                }

            }


        }
        else {
            $theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo '</div>';
    }
    elseif ($do == 'Delete') {

        echo '<h1 class="text-center">Delete Member</h1>';
        echo '<div class="container">';

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $check = checkItem('UserID', 'users', $userid);

        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM users WHERE UserID = :zuser');
            $stmt->bindParam(':zuser', $userid);
            $stmt->execute();
            $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Deleted</div>';
            redirectHome($theMsg, 'back');

        }
        else {
            $theMsg = '<div class="alert alert-danger">There is no such id</div>';
            redirectHome($theMsg);

        }
        echo '</div>';

    }
    elseif ($do = 'Activate') {
        echo '<h1 class="text-center">Activate Member</h1>';
        echo '<div class="container">';

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        $check = checkItem('UserID', 'users', $userid);

        if ($check > 0) {
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));
            $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg, 'back');

        }
        else {
            $theMsg = '<div class="alert alert-danger">There is no such id</div>';
            redirectHome($theMsg);

        }
        echo '</div>';

    }
    include $tpl . 'footer.php';

}
else {
    header('Location: index.php');
    exit();
}
ob_end_flush();

?>