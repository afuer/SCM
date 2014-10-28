<?php

include '../lib/DbManager.php';
$object_name = 'user_level';
$object_id = strtoupper($object_name) . '_ID';


$Id = getParam('search_id');

$sql = "SELECT COUNT(*) FROM master_user WHERE USER_LEVEL_ID='$Id'";
$result = findValue($sql);

if ($result > 0) {
    echo json_encode(array('msg' => "Can't Remove"));
} else {
    include '../lib/master_page_save.php';

    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('msg' => "Can't Remove"));
    }
}
?>