<?php

include '../lib/DbManager.php';
$className = 'gander';

$search = getParam('search');


/* Do not Chage below Code */
include "gander.php";
$gander = new gander();

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
$offset = ($page - 1) * $rows;
$result = $gander->getDataGrid($offset, $rows, $search);

echo $result;
?>

