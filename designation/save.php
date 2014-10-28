<?php

include '../lib/DbManager.php';
$object_name = 'designation';
$object_id = strtoupper($object_name) . '_ID';

$db->OpenDb();
include '../lib/master_page_save.php';



if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>