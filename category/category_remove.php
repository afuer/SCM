<?php
include '../lib/DbManager.php';
include 'category.php';


 $id = intval(getParam('category_id'));
 

try {
    $category = new Category();
    $result =  $category->delete($id);
} 
    catch (Exception $exc) {
    echo $exc->getTraceAsString();
}


if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>