<?php
include '../lib/DbManager.php';
include ('employee.php');

$val = $_REQUEST['val'];
$employee = new employee();
$suplierName = $employee->supplierName($val);

 ?>
   <td><?php echo $suplierName; ?></td>
