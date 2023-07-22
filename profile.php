<?php
ob_start();

session_start();

$footer = '';

$pageTitle = 'Profile';

include "init.php";

if (isset($_SESSION['user'])) {

    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];
?>
<h1 class="text-center">My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                <ul class="list-unstyled">
                <li>
                    <i class="fa fa-unlock-alt fa-fw"></i>
                    <span>Login Name</span> : <?php echo $info['Username']; ?> 
                </li>
                <li>
                    <i class="fa fa-envelope-o fa-fw"></i>
                    <span>Email</span> : <?php echo $info['Email']; ?> 
                </li>
                <li>
                    <i class="fa fa-user fa-fw"></i>
                    <span>Full Name</span> : <?php echo $info['FullName']; ?> 
                </li>
                <li>
                    <i class="fa fa-calendar fa-fw"></i>
                    <span>Register Date</span> : <?php echo $info['Date']; ?> 
                </li>
                <li>
                    <i class="fa fa-tags fa-fw"></i>
                    <span>Fav Category</span> : 
                </li>
                </ul>
                <a href="editProfile.php" class="btn btn-default">Edit My Information</a>
            </div>
        </div>
    </div>
</div>

<div id="my-ads" class="my-ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Items</div>
            <div class="panel-body">
<?php
    $myItems = getAllFrom("*", "items", "Item_ID", "where Member_ID=$userid", "");
    if (!empty($myItems)) {
        echo '<div class="row">';
        foreach ($myItems as $item) {
            echo '<div class="col-sm-6 col-md-3">';
            echo '<a class="items" href="items.php?itemid=' . $item['Item_ID'] . '">';
            echo '<div class="thumbnail item-box">';
            if ($item['Approve'] == 0) {
                echo '<span class="approve-status">Waiting Approval</span>';
            }
            echo '<span class="price-tag">$' . $item['Price'] . '</span>';

            if ($item['avatar'] !== NULL) { ?>
        
                <img src="../../../eCommerce/admin/uploads/item-avatars/<?php echo $item['avatar']; ?>" alt="" />
            <?php
            }
            else { ?>
                <img src="img.png" alt="" />
            <?php
            }

            echo '<div class="caption">';
            echo '<h3><strong>' . $item['Name'] . '</strong></h3>';
            echo '<p>' . $item['Description'] . '</p>';
            echo '<div class="date">' . $item['Add_Date'] . '</div>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
    }
    else {
        echo 'There Is No Ads To Show, Creat <a href="newad.php">New Ad </a>';
    }
?>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Latest Comments</div>
            <div class="panel-body">
                <?php
    $myComments = getAllFrom("comment", "comments", "c_id", "where user_id=$userid", "");

    if (!empty($myComments)) {
        foreach ($myComments as $comment) {
            echo '<p>' . $comment['comment'] . '</p>';
        }
    }
    else {
        echo 'There\'s No Comments To Show';
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
