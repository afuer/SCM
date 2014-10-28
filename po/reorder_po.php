<?php
include'../lib/DbManager.php';


include("../body/header.php");
$chkproduct00 = getParam('chkproduct00');
$_SESSION['chkproduct00'] = $chkproduct00;
// print_r($chkproduct00);
?>
<form id="form1" name="form1" method="post" action="temp_view2.php">
    <input type="hidden" name="chkproduct00[]" value="<?php echo $chkproduct00; ?>" />
    <table border="0" width="100%"  id="hor-minimalist-b" cellspacing="0" cellpadding="0">
        <tr>
            <td width="33%"><div align="center">Reorder from PO </div></td>
            <td width="3%">&nbsp;</td>
            <td width="64%"><input type="text" name="reorderpo" /></td>
        </tr>
    </table>
    <button type="submit" name = "btnsupplier" class="button"/><span class = "icon plus"></span>Submit PO</button>
</form>
