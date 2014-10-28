<?php

include '../lib/DbManager.php';
$className = 'marital_status';

$search = getParam('search');


/* Do not Chage below Code*/
include "$className.php";
$className = new $className();

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;
$result = $className->getDataGrid($offset, $rows, $search);

echo $result;
?>

