<?php
include '../lib/DbManager.php';
include "DAL.php";
include 'DTO.php';
//$className = 'employee';
$employee = new DAL();
$dto = new DTO();


/* Do not Chage below Code */
$mode = getParam('mode');

if ($mode == 'search') {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;

    if (getParam('dateFrom') != '') {
        $date_from = date("Y-m-d", strtotime(getParam('dateFrom')));
    }
    if (getParam('dateTo') != '') {
        $date_to = date("Y-m-d", strtotime(getParam('dateTo')));
    }

    $dto->dateFrom = $date_from;
    $dto->dateTo = $date_to;
    $dto->cardNo = getParam('cardNo');
    $dto->firstName = getParam('firstName');
    $dto->officeTypeId = getParam('officeTypeId');
    $dto->designationId = getParam('designationId');
    $dto->IsActive= getParam('IsActive');
    //$dto->department= getParam('department');

    $result = $employee->search($offset, $rows, $dto);
} else {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
    $offset = ($page - 1) * $rows;
    $result = $employee->getDataGrid($offset, $rows);
}

echo $result;
?>

