<?php
include '../lib/DbManager.php';
$WfGroupList = $db->rs2array("SELECT workflow_group_id,workflow_name  FROM workflow_group");
?>
<td>Reported Group:</td>
<td colspan="<?php echo $Type_count; ?>"><?php combobox('WdReported', $WfGroupList, '', true, '', 'AjaxDefultWorkFlow'); ?></td>