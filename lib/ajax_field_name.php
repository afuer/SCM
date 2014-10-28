<?php
include 'include.php';

$val = getParam('val');

$field_list = rs2array(query("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$val' AND TABLE_SCHEMA='$DB_NAME'"));

?>
<td><?php comboBox2('field_name', $field_list, '', TRUE, 'required', 'ajax_display_name', "$val"); ?></td>