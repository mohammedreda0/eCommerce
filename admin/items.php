<?php

ob_start();

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $stmt = $con->prepare("SELECT items.*,categories.Name AS category_name,users.Username FROM items INNER JOIN categories ON categories.ID=items.Cat_ID INNER JOIN users ON users.UserID=items.Member_ID ORDER BY Item_ID DESC");
        $stmt->execute();
        $items = $stmt->fetchAll();
        if (!empty($items)) {
?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table manage-members text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Avatar</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>

                    <?php

            foreach ($items as $item) {
                echo '<tr>';
                echo '<td>' . $item['Item_ID'] . '</td>';
                echo '<td>';
                if (empty($item['avatar'])) {
                    echo 'No Image';
                }
                else {
                    echo '<img src="uploads/item-avatars/' . $item['avatar'] . '"alt="" />';
                }
                echo '<td>' . $item['Name'] . '</td>';
                echo '<td>' . $item['Description'] . '</td>';
                echo '<td>' . $item['Price'] . '</td>';
                echo '<td>' . $item['Add_Date'] . '</td>';
                echo '<td>' . $item['category_name'] . '</td>';
                echo '<td>' . $item['Username'] . '</td>';

                echo '<td>
                    <a href="items.php?do=Edit&itemid=' . $item['Item_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="items.php?do=Delete&itemid=' . $item['Item_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
                if ($item['Approve'] == 0) {
                    echo '<a href="items.php?do=Approve&itemid=' . $item['Item_ID'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                }
                echo '</td>';
                echo '</tr>';
            }

?>
                    
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new Item</a>
        </div>
        <?php
        }
        else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Record To Show</div>';
            echo '<a href="items.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new Item</a>';
            echo '</div>';
        }
    }
    elseif ($do == 'Add') { ?>
        <h1 class="text-center">Add New Item</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="name" required="required" placeholder="Name of the item">
                        
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="description" required="required" placeholder="Description of the item">
                        
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="price" required="required" placeholder="Price of the item">
                        
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="country" required="required" placeholder="Country of Made">
                        
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Very Old</option>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Member</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
        $allMembers = getAllFrom("*", "users", "UserID", "", "");
        foreach ($allMembers as $user) {
            echo '<option value="' . $user['UserID'] . '">' . $user['Username'] . '</option>';
        }

?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Category</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
        $allCats = getAllFrom("*", "categories", "ID", "where parent = 0", "");
        foreach ($allCats as $cat) {
            echo '<option value="' . $cat['ID'] . '">' . $cat['Name'] . '</option>';
            $childCats = getAllFrom("*", "categories", "ID", "where parent = {$cat['ID']}", "");
            foreach ($childCats as $child) {
                echo '<option value="' . $child['ID'] . '">--- ' . $child['Name'] . '</option>';
            }

        }

?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="tags" placeholder="Separate Tags With Comma(,)">
                        
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Item Avatar</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="file" class="form-control" name="avatar" required="required">
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" class="btn btn-primary btn-sm" value="Add Item">
                    </div>
                </div>
            </form>

        </div>

<?php
    }
    elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Insert Item</h1>';
            echo '<div class="container">';

            $avatarName = $_FILES['avatar']['name'];
            $avatarSize = $_FILES['avatar']['size'];
            $avatarTmp = $_FILES['avatar']['tmp_name'];
            $avatarType = $_FILES['avatar']['type'];

            $avatarAllowedExtensions = array("jpeg", "jpg", "png", "gif");
            $ex = explode('.', $avatarName);
            $avatarExtension = strtolower(end($ex));

            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];
            $tags = $_POST['tags'];


            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = 'Name can\'t be <strong>empty</strong>';
            }

            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be <strong>empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>empty</strong>';
            }

            if ($status == 0) {
                $formErrors[] = 'You must choose the <strong>status</strong>';
            }

            if ($member == 0) {
                $formErrors[] = 'You must choose the <strong>member</strong>';
            }

            if ($cat == 0) {
                $formErrors[] = 'You must choose the <strong>category</strong>';
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
                move_uploaded_file($avatarTmp, "uploads/item-avatars/" . $avatar);

                $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Cat_ID, Member_ID, tags, avatar) VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags,:zavatar)");
                $stmt->execute(array(
                    ':zname' => $name,
                    ':zdesc' => $desc,
                    ':zprice' => $price,
                    ':zcountry' => $country,
                    ':zstatus' => $status,
                    ':zcat' => $cat,
                    ':zmember' => $member,
                    ':ztags' => $tags,
                    ':zavatar' => $avatar

                ));
                echo '<div class="container">';
                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Inserted</div>';
                redirectHome($theMsg, 'back');
                echo '</div>';
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
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Item</h1>
                    <div class="container">
                        <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="itemid" value="<?php echo $itemid; ?>">


                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" class="form-control" name="name" required="required" placeholder="Name of the item" value="<?php echo $item['Name']; ?>">
                                    
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" class="form-control" name="description" required="required" placeholder="Description of the item" value="<?php echo $item['Description']; ?>">
                                    
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Price</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" class="form-control" name="price" required="required" placeholder="Price of the item" value="<?php echo $item['Price']; ?>">
                                    
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Country</label>
                                <div class="col-sm-10 col-md-6">
                                    <input type="text" class="form-control" name="country" required="required" placeholder="Country of Made" value="<?php echo $item['Country_Made']; ?>">
                                    
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Status</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="status">
                                        <option value="1" <?php if ($item['Status'] == 1) {
                echo 'selected';
            }?>>New</option>
                                        <option value="2" <?php if ($item['Status'] == 2) {
                echo 'selected';
            }?>>Like New</option>
                                        <option value="3" <?php if ($item['Status'] == 3) {
                echo 'selected';
            }?>>Used</option>
                                        <option value="4" <?php if ($item['Status'] == 4) {
                echo 'selected';
            }?>>Very Old</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Member</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="member">
                                        <?php
            $stmt = $con->prepare("SELECT * FROM users");
            $stmt->execute();
            $users = $stmt->fetchAll();
            foreach ($users as $user) {
                echo '<option value="' . $user['UserID'] . '"';
                if ($item['Member_ID'] == $user['UserID']) {
                    echo 'selected';
                }
                echo '>' . $user['Username'] . '</option>';
            }

?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                <label class="col-sm-2 control-label">Category</label>
                                <div class="col-sm-10 col-md-6">
                                    <select name="category">
                                        <?php
            $allCats = getAllFrom("*", "categories", "ID", "", "");

            foreach ($allCats as $cat) {
                echo '<option value="' . $cat['ID'] . '"';
                if ($item['Cat_ID'] == $cat['ID']) {
                    echo 'selected';
                }
                echo '>' . $cat['Name'] . '</option>';
            }

?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" class="form-control" name="tags" placeholder="Separate Tags With Comma(,)" value="<?php echo $item['tags']; ?>">
                        
                    </div>
                </div>

                            <div class="form-group form-group-lg">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="submit" class="btn btn-primary btn-sm" value="Save Item">
                                </div>
                            </div>
                        </form>
                            <?php
            $stmt = $con->prepare("SELECT comments.*,users.Username FROM comments INNER JOIN users ON users.UserID=comments.user_id WHERE item_id=?");
            $stmt->execute(array($itemid));
            $rows = $stmt->fetchAll();

            if (!empty($rows)) {

?>
        <h1 class="text-center">Manage [ <?php echo $item['Name']; ?> ] Comments</h1>
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>Comment</td>
                        <td>User Name</td>
                        <td>Added Date</td>
                        <td>Control</td>
                    </tr>

                    <?php

                foreach ($rows as $row) {
                    echo '<tr>';
                    echo '<td>' . $row['comment'] . '</td>';
                    echo '<td>' . $row['Username'] . '</td>';
                    echo '<td>' . $row['comment_date'] . '</td>';
                    echo '<td>
                    <a href="comments.php?do=Edit&comid=' . $row['c_id'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                    <a href="comments.php?do=Delete&comid=' . $row['c_id'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
                    if ($row['status'] == 0) {
                        echo '<a href="comments.php?do=Approve&comid=' . $row['c_id'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                    }

                    echo '</td>';
                    echo '</tr>';
                }

?>
                    
                </table>
            </div>
            <?php
            }?>
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
        echo '<h1 class="text-center">Update Item</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['itemid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $price = $_POST['price'];
            $country = $_POST['country'];
            $status = $_POST['status'];
            $member = $_POST['member'];
            $cat = $_POST['category'];
            $tags = $_POST['tags'];



            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = 'Name can\'t be <strong>empty</strong>';
            }

            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be <strong>empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>empty</strong>';
            }


            foreach ($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }

            if (empty($formErrors)) {
                $stmt = $con->prepare('UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Member_ID = ?, Cat_ID = ?, tags= ? WHERE Item_ID = ?');
                $stmt->execute(array($name, $desc, $price, $country, $status, $member, $cat, $tags, $id));

                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
                redirectHome($theMsg, 'back');
            }


        }
        else {
            $theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo '</div>';
    }
    elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Item</h1>';
        echo '<div class="container">';

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $check = checkItem('Item_ID', 'items', $itemid);

        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM items WHERE Item_ID = :zitem');
            $stmt->bindParam(':zitem', $itemid);
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
    elseif ($do = 'Approve') {
        echo '<h1 class="text-center">Approve Item</h1>';
        echo '<div class="container">';

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        $check = checkItem('Item_ID', 'items', $itemid);

        if ($check > 0) {
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
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
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>