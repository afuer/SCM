<?php
include '../lib/DbManager.php';
include ('employee.php');

$val = $_REQUEST['val'];
$employee = new employee();
$suplierList = json_decode($employee->supplierCombo());
//echo $lineManager;
if ($val == '0' OR $val == '2') {
    ?>
    <td>Supplier:</td>
    <td><?php combobox("SUPPLIER_ID", $suplierList, $var->SUPPLIER_ID, true) ?></td>
    <td>Salary:</td>
    <td><input type="text" name="SALARY" value="<?php echo $var->SALARY; ?>"/></td>
<?php } ?>
   
