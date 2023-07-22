<?php

$do = '';

$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

// if(isset($_GET['do'])){
//     $do = $_GET['do'];
// }else{
//     $do = 'Manage';
// }

if($do == 'Manage'){
    echo 'Welcome you are in manage page';
    echo '<a href="page.php?do=Add">Add new category</a>';

}elseif($do == 'Add'){
    echo 'Welcome you are in Add page';

}elseif($do == 'Insert'){
    echo 'Welcome you are in Insert page';

}else{
    echo 'Error there\'s no page with this name';

}