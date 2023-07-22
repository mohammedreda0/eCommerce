<?php

function getAllFrom($field, $table, $orderfield, $where = NULL, $and = NULL, $ordering = "DESC")
{
    global $con;
    $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    }
    else {
        echo 'Default';
    }
}

function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if ($url === null) {
        $url = 'index.php';
        $link = 'Home Page';
    }
    else {

        if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
            $url = $_SERVER['HTTP_REFERER'];
            $link = 'Previous Page';
        }
        else {
            $url = 'index.php';
            $link = 'Home Page';
        }
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You will be directed to $link after <strong>$seconds</seconds> Seconds</div>";
    header("refresh:$seconds,url=$url");
    exit();
}

function checkItem($select, $from, $value)
{
    global $con;
    $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $statement->execute(array($value));
    $count = $statement->rowCount();
    return $count;
}

function countItems($item, $table)
{
    global $con;
    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

function getLatest($select, $table, $order, $limit = 5)
{
    global $con;
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;
}