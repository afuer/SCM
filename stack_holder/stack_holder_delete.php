<?php

include '../lib/DbManager.php';

include('DAL.php');

$dal = new DAL();

include("../body/header.php");

$mode = getParam('mode');
$search_id = getParam('search_id');

if ($mode == 'delete') {
   $row = $dal->DeleteAll($search_id); 
}


?>







