<?php
include('../framework2/include.php');
$val = getParam("val");


if ($val != "") {

    $affected_field_list = rs2array(query("SELECT object_id, display_name FROM master_mod WHERE object_id='$val' ORDER BY _sort"));
}
?>
<td> <?php comboBox('affected_field_name', $affected_field_list, '', TRUE) ?><td/>





