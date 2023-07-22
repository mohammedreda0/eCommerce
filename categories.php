<?php
ob_start();
session_start();

include "init.php";


$footer = '';
?>

<div class="container">
    <h1 class="text-center"><?php echo $_GET['pagename']; ?></h1>
<div class="row">
<?php
if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
    $category = intval($_GET['pageid']);
    $allItems = getAllFrom("*", "items", "Item_ID", "WHERE Cat_ID = {$category}", "AND Approve = 1");
    foreach ($allItems as $item) { ?>
        <div class="col-sm-6 col-md-3">
        <a class="items" href="items.php?itemid=<?php echo $item['Item_ID']; ?>">
        <div class="thumbnail item-box">
        <span class="price-tag">$<?php echo $item['Price']; ?></span>
        <?php

        if ($item['avatar'] !== NULL) { ?>
        
            <img src="../../../eCommerce/admin/uploads/item-avatars/<?php echo $item['avatar']; ?>" alt="" />
        <?php
        }
        else { ?>
            <img src="img.png" alt="" />
        <?php
        }

?>
        
        <div class="caption">
        <h3><strong><?php echo $item['Name']; ?></strong></h3>
        <p><?php echo $item['Description']; ?></p>
        <div class="date"><?php echo $item['Add_Date']; ?></div>
        </div>
        </div>
        </a>
        </div>
        <?php
    }
}
else {
    echo 'You Must Add Page ID';
}
?>
</div>
</div>

<?php include $tpl . 'footer.php';
ob_end_flush();

?>
