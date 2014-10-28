<?php
include 'include.php';

$val = getParam('val');
$TABLE_NAME=  getParam('id');

//$field_list=  rs2array(query("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='user_details' AND COLUMN_NAME='$val'"));
$comments = find("SELECT COLUMN_COMMENT, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME ='$TABLE_NAME' AND COLUMN_NAME='$val'");
if (isset($comments->COLUMN_COMMENT)) {
    $explode = explode(',', $comments->COLUMN_COMMENT);
    $explode_zero = isset($explode[0]) ? $explode[0] : '';
} else {
    $explode_zero = '';
}
?>
<td><input type="text" name="display_name" value="<?php echo $explode_zero; ?>"/></td>