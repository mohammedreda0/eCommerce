<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css">
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt.css">
    <link rel="stylesheet" href="<?php echo $css; ?>front.css" />
    
</head>
<body>
    

    <nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php
if (isset($_SESSION['user'])) { ?>
    
    <div class="logged-in">
        <?php

    if (isset($_SESSION['avatar']) && $_SESSION['avatar'] !== NULL) {

?>
            <img class="my-image img-thumbnail img-circle" src="../../../eCommerce/admin/uploads/avatars/<?php echo $_SESSION['avatar']; ?>" alt="" />
            <?php
    }
    else {
?>
        
            <img class="my-image img-thumbnail img-circle" src="img.png" alt="" />
            <?php
    }


?>
    
    <div class="btn-group my-info">
        <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <?php echo $sessionUser; ?>
            <span class="caret"></span>
        </span>
        <ul class="dropdown-menu">
            <li><a href="index.php">Home page</a></li>
            <li><a href="profile.php">My Profile </a></li>
            <li><a href="newad.php">New Item </a></li>
            <li><a href="profile.php#my-ads">My Items </a></li>
            <li><a href="logout.php">LogOut </a></li>
        </ul>
    </div>
</div>
<?php
}
else { ?>
    <a class="navbar-brand pull-right" href="index.php">Homepage</a>
    <?php
}
?>
        
        </div>


        
        <div class="collapse navbar-collapse" id="app-nav">
        <ul class="nav navbar-nav navbar-right">
            <?php
$allCats = getAllFrom("*", "categories", "ID", "where parent=0", "", "ASC");
foreach ($allCats as $cat) {
    echo '<li><a href="categories.php?pageid=' . $cat['ID'] . '&pagename=' . $cat['Name'] . '">' . $cat['Name'] . '</a></li>';
}?>

<li>
<a href="contact.php" class="btn btn-warning extra">
                Contact Us
    </a>
</li>

    <?php
if (!isset($_SESSION['user'])) { ?>
<li>
<a href="login.php" class="btn btn-info extra">
                Login/SignUp
    </a>
</li>
<?php
}

?>
        </ul> 
        </div>
    </div>
    </nav>













    