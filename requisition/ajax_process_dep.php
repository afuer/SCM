<?php
include '../lib/DbManager.php';
$val = getParam('val');
$db = new DbManager();

$sql = "SELECT * FROM process_dept WHERE REQUISITION_TYPE_ID='$val'";
$SqlResult = $db->query($sql);
?>

<td>
    <?php
    while ($row = mysql_fetch_object($SqlResult)) {
        echo " <input type='radio' class='required' name='processDeptId' value='$row->PROCESS_DEPT_ID' checked='true'/>$row->PROCESS_DEPT_NAME  ";
    }
    ?>
</td>
