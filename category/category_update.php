<?php

include '../lib/DbManager.php';
include 'category.php';



 $id = intval(getParam('category_id'));

$categoryName = getParam('CATEGOTY_NAME');
$categoryDepartmentId = getParam('PROCESS_DEPARTMENT_ID');
$categoryDescription = getParam('DESCRIPTION');



try {
    $db = new DbManager();
    $category = new Category();
    
  $result =  $category->update($id,$categoryName, $categoryDepartmentId, $categoryDescription,$user_name);
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}


if ($result) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('msg' => 'Some errors occured.'));
}
?>