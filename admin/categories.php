<?php

ob_start();

session_start();

$pageTitle = 'Categories';

if (isset($_SESSION['Username'])) {
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $sort = 'asc';
        $sort_array = array('asc', 'desc');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }

        $stmt2 = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY Ordering $sort");
        $stmt2->execute();
        $cats = $stmt2->fetchAll();

        if (!empty($cats)) {

?>


        <h1 class="text-center">Manage Categories</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i> Manage Categories
                    <div class="option pull-right">
                        <i class="fa fa-sort"></i> Ordering: [
                        <a class="<?php if ($sort == 'asc') {
                echo 'active';
            }?>" href="?sort=asc">ASC</a> |
                        <a class="<?php if ($sort == 'desc') {
                echo 'active';
            }?>" href="?sort=desc">DESC</a> ]
                        <i class="fa fa-eye"></i> View: [
                        <span class="active" data-view="full">Full</span> |
                        <span data-view="classic">Classic</span> ]
                    </div>
                </div>
                <div class="panel-body">
                    <?php

            foreach ($cats as $cat) {
                echo '<div class="cat">';
                echo '<div class="hidden-buttons">';
                echo '<a href="categories.php?do=Edit&catid=' . $cat['ID'] . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                echo '<a href="categories.php?do=Delete&catid=' . $cat['ID'] . '" class="confirm btn btn-xs btn-danger"><i class="fa fa-close"></i> Delete</a>';
                echo '</div>';
                echo '<h3>' . $cat['Name'] . '</h3>';
                echo '<div class="full-view">';
                echo '<p>';
                if ($cat['Description'] == '') {
                    echo 'This category has no description';
                }
                else {
                    echo $cat['Description'];
                }
                echo '</p>';
                if ($cat['Visibility'] == 1) {
                    echo '<span class="visibility"><i class="fa fa-eye"></i> Hidden</span>';
                }
                if ($cat['Allow_Comment'] == 1) {
                    echo '<span class="commenting"><i class="fa fa-close"></i> Comment Disabled</span>';
                }
                if ($cat['Allow_Ads'] == 1) {
                    echo '<span class="advertises"><i class="fa fa-close"></i> Ads Disabled</span>';
                }
                echo '</div>';
                $childCats = getAllFrom("*", "categories", "ID", "where parent={$cat['ID']}", "", "ASC");
                if (!empty($childCats)) {
                    echo '<h4 class="child-head">Child Categories</h4>';
                    echo '<ul class="list-unstyled child-cats">';
                    foreach ($childCats as $c) {
                        echo '<li class="child-link">
                                <a href="categories.php?do=Edit&catid=' . $c['ID'] . '">' . $c['Name'] . '</a>
                                <a href="categories.php?do=Delete&catid=' . $c['ID'] . '" class="show-delete confirm"> Delete</a>
                            </li>';
                    }
                    echo '</ul>';
                }
                echo '</div>';
                echo '<hr>';

            }

?>
                </div>
            </div>
            <a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
        </div>

<?php
        }
        else {
            echo '<div class="container">';
            echo '<div class="nice-message">There\'s No Record To Show</div>';
            echo '<a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add new Category</a>';
            echo '</div>';
        }
    }
    elseif ($do == 'Add') { ?>

        <h1 class="text-center">Add New Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" auto-complete="off" required="required" placeholder="Name of the category">
                            
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Descripe the category">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="ordering" placeholder="Number to arrange the category">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
        $allCats = getAllFrom("*", "categories", "ID", "WHERE parent=0", "");
        foreach ($allCats as $cat) {
            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
        }
?>
                            </select>    
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" checked />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" checked />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" />
                                <label for="ads-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary btn-lg" value="Add Category">
                        </div>
                    </div>
                </form>

            </div>
    
    <?php
    }
    elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<h1 class="text-center">Insert Category</h1>';
            echo '<div class="container">';
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $parent = $_POST['parent'];
            $order = $_POST['ordering'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];


            $check = checkItem("Name", "categories", $name);
            if ($check == 1) {
                $theMsg = '<div class="alert alert-danger">Category exists</div>';
                redirectHome($theMsg, 'back');
            }
            else {


                $stmt = $con->prepare("INSERT INTO categories(Name, Description,parent , Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES(:zname,:zdesc,:zparent,:zorder,:zvisible,:zcomment,:zads)");
                $stmt->execute(array(
                    ':zname' => $name,
                    ':zdesc' => $desc,
                    ':zparent' => $parent,
                    ':zorder' => $order,
                    ':zvisible' => $visible,
                    ':zcomment' => $comment,
                    ':zads' => $ads
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
            redirectHome($theMsg, 'back');
            echo '</div>';
        }
        echo '</div>';
    }
    elseif ($do == 'Edit') {
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
        $stmt->execute(array($catid));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="catid" value="<?php echo $catid; ?>">

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="name" required="required" placeholder="Name of the category" value="<?php echo $cat['Name']; ?>">
                            
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="description" placeholder="Descripe the category" value="<?php echo $cat['Description']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" class="form-control" name="ordering" placeholder="Number to arrange the category" value="<?php echo $cat['Ordering']; ?>">
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Parent?</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php
            $allCats = getAllFrom("*", "categories", "ID", "WHERE parent=0", "");
            foreach ($allCats as $c) {
                echo "<option value='" . $c['ID'] . "'";
                if ($cat['parent'] == $c['ID']) {
                    echo ' selected';
                }
                echo ">" . $c['Name'] . "</option>";
            }
?>
                            </select>    
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibile</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cat['Visibility'] == 0) {
                echo 'checked';
            }?> />
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cat['Visibility'] == 1) {
                echo 'checked';
            }?> />
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Commenting</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cat['Allow_Comment'] == 0) {
                echo 'checked';
            }?> />
                                <label for="com-yes">Yes</label>
                            </div>
                            <div>
                                <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cat['Allow_Comment'] == 1) {
                echo 'checked';
            }?> />
                                <label for="com-no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cat['Allow_Ads'] == 0) {
                echo 'checked';
            }?> />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cat['Allow_Ads'] == 1) {
                echo 'checked';
            }?> />
                                <label for="ads-no">No</label>
                            </div>
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
        echo '<h1 class="text-center">Update Category</h1>';
        echo '<div class="container">';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['catid'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $order = $_POST['ordering'];
            $parent = $_POST['parent'];
            $visible = $_POST['visibility'];
            $comment = $_POST['commenting'];
            $ads = $_POST['ads'];

            // $check = checkItem("Name", "categories", $name);
            // if ($check == 1) {
            //     $theMsg = '<div class="alert alert-danger">Category exists</div>';
            //     redirectHome($theMsg, 'back');
            // }


            $stmt = $con->prepare('UPDATE categories SET Name = ?, Description = ?, Ordering = ?, parent=?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?');
            $stmt->execute(array($name, $desc, $order, $parent, $visible, $comment, $ads, $id));

            $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>';
            redirectHome($theMsg, 'back');



        }
        else {
            $theMsg = '<div class="alert alert-danger">you cant browse this page directly</div>';
            redirectHome($theMsg);
        }
        echo '</div>';
    }
    elseif ($do == 'Delete') {
        echo '<h1 class="text-center">Delete Category</h1>';
        echo '<div class="container">';

        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

        $check = checkItem('ID', 'categories', $catid);

        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM categories WHERE ID = :zid');
            $stmt->bindParam(':zid', $catid);
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
    include $tpl . 'footer.php';
}
else {
    header('Location:index.php');
    exit();
}
ob_end_flush();
?>