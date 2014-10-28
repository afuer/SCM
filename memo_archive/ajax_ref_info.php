<?php

include_once '../lib/DbManager.php';
$val = $_REQUEST['val'];
$db = new DbManager();
$db->OpenDb();
//$refSQL1="AAAAAA";

$refSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_REF,PAYMENT_METHOD FROM memo_archive WHERE MEMO_REF='$val'";
$refObj = find($refSQL);
/*
if ($refObj->MEMO_TYPE=='' || $refObj->MEMO_DATE=='' || $refObj->MEMO_INFO_REF==''){
    
}
else
 * 
 */
    //echo "AAAAAAAAAAAAA";
if($refObj==''){
    echo 'Invalid Memo Info Ref';
}
 else {
?> 
<table>
    <tr>
        <td>Type:</td>
        <td><?php echo  $refObj->MEMO_TYPE;?></td>
        <td>Date:</td>
        <td><?php echo  $refObj->MEMO_DATE;?></td>
        <td>Reference:</td>
        <td><?php echo  $refObj->MEMO_INFO_REF;?></td>
    </tr>
</table>
<?php
 }
$db->CloseDb();
?>

