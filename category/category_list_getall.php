<?php

include '../lib/DbManager.php';
include 'category.php';

//$branch = new Branch();

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;

$result = array();

try {
    $category = new Category();
    
    $result["total"] = $category->count() ;
} 
catch (Exception $exc) {
    echo $exc->getTraceAsString();
    //rollBack;
}

//$result["total"] = $branch->count() ;




try {
    $category = new Category();
    $sql_result = $category->getAll($offset, $rows);
} 
catch (Exception $exc) {
    echo $exc->getTraceAsString();
    //rollBack;
}


$items = array();
while ($row = fetch_object($sql_result)) {
    array_push($items, $row);
}

//mysql_free_result($sql_result);

$result["rows"] = $items;
echo json_encode($result);
?>