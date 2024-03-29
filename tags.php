<?php
ob_start();
session_start();

include "init.php"; ?>

<div class="container">
    
<div class="row">
<?php
if (isset($_GET['name'])) {
    $tag = $_GET['name'];
    echo '<h1 class="text-center">' . $tag . '</h1>';
    $tagItems = getAllFrom("*", "items", "Item_ID", "where tags like '%$tag%'", "AND Approve = 1");
    foreach ($tagItems as $item) {
        echo '<div class="col-sm-6 col-md-3">';
        echo '<div class="thumbnail item-box">';
        echo '<span class="price-tag">$' . $item['Price'] . '</span>';
            
            if($item['avatar'] !== NULL){?>
        
                <img class="img-responsive" src="../../../eCommerce/admin/uploads/item-avatars/<?php echo $item['avatar']; ?>" alt="" />
            <?php
            }else {?>
                <img class="img-responsive" src="img.png" alt="" />
            <?php
            }

        echo '<div class="caption">';
        echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
        echo '<p>' . $item['Description'] . '</p>';
        echo '<div class="date">' . $item['Add_Date'] . '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
else {
    echo 'You Must Enter Tag Name';
}
?>
</div>
</div>

<?php include $tpl . 'footer.php';
ob_end_flush();

?>
