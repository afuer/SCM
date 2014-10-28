<?php
include '../lib/DbManager.php';

$val = getParam("val");
$name = getParam("name");
// 	
if ($val == "") {
    echo "No record found";
} else {
    $rec = find("select emp.EMPLOYEE_ID, emp.FIRST_NAME, deg.DESIGNATION_NAME 
    from employee emp 
    left join designation deg on emp.DESIGNATION_ID=deg.DESIGNATION_ID
    where emp.CARD_NO='$val'");
    if ($rec->EMPLOYEE_ID == "") {
        echo "No record found";
    } else {
        ?>
        <div style="float:left; width:50%"><input type="hidden" name="name_<?php echo $name; ?>" value="<?php echo $rec->FIRST_NAME; ?>" /><?php echo $rec->FIRST_NAME; ?></div>
        <div style="float:left; width:50%"><input type="hidden" name="designation_<?php echo $name; ?>" value="<?php echo $rec->DESIGNATION_NAME; ?>" />(<?php echo $rec->DESIGNATION_NAME; ?>)</div>
        <?php
    }
}
?>