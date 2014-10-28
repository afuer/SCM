<?php
include_once '../lib/DbManager.php';
$searchID = getParam('id');

$selectSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";

$memoObj = find($selectSQL);

$appTypeSQL = "SELECT FIRST_NAME, LAST_NAME, CARD_NO, approveType, d.DESIGNATION_NAME 
FROM mem_manage_emp_det ed
LEFT OUTER JOIN employee em ON em.EMPLOYEE_ID = ed.empID
LEFT JOIN designation d ON d.DESIGNATION_ID=em.DESIGNATION_ID
WHERE memo_archive_id='$searchID' 
ORDER BY _sort";
$TypeResult = query($appTypeSQL);




$memoType = $memoObj->MEMO_TYPE;
$memoDate = $memoObj->MEMO_DATE;
$memoInfoRef = $memoObj->MEMO_INFO_REF;
$paymentMethod = $memoObj->PAYMENT_METHOD;
$memoDetails = $memoObj->MEMO_DETAILS;
$memoCategory = $memoObj->MEMO_CATEGORY;
$approveAmount = $memoObj->APPROVED_AMOUNT;
$remarks = $memoObj->REMARKS;
$payMethod = $memoObj->PAYMENT_METHOD;
$memoSub = $memoObj->MEMO_SUBJECT;
$memoRef = $memoObj->MEMO_REF;
$boardDate = $memoObj->BOARD_DATE;
$boardNo = $memoObj->BOARD_NO;

include '../body/header.php';
//include_once '../body/body_header.php';
?>


<div class="panel-header">Memo View</div>
<div style="padding: 20px 20px; background: white;">

    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table">
            <tr class='fitem'>
                <td>MEMO REFERENCE :</td>
                <td><?php echo $memoRef; ?></td>
            </tr>

            <tr class='fitem'>                        
                <td width="200">MEMO INFO REF: </td>
                <td width="550"><?php echo $memoInfoRef; ?></td>
            </tr>

            <tr class='fitem'>                        
                <td>SUBJECT :</td>
                <td><?php echo $memoSub; ?></td>
            </tr>

            <tr class='fitem'>                        
                <td> MEMO TYPE :</td>
                <td><?php echo $memoType; ?></td>
            </tr>

            <?php if ($memoType == 'board') { ?>
                <tr class='fitem'>
                    <td></td>
                    <td> BOARD NO:
                        <?php echo $boardNo; ?>  DATE:<?php echo $boardDate; ?> </td>
                </tr>
                <tr class='fitem'>
                    <td valign="top">Employee: </td>
                    <td>
                        <table id="productTab" class="ui-state-default">
                            <thead>
                            <th width="10">SL</th>
                            <th>EMPLOYEE</th>
                            <th width="100">APPROVAL TYPE</th>
                            </thead>
                            <tbody> 
                                <?php while ($TypeObj = fetch_object($TypeResult)) { ?>
                                    <tr>
                                        <td><?php echo++$proSL; ?></td> 
                                        <td><?php echo $TypeObj->CARD_NO . '->' . $TypeObj->FIRST_NAME . ' ' . $TypeObj->LAST_NAME . '(' . $TypeObj->DESIGNATION_NAME . ')'; ?></td>
                                        <td><?php echo $TypeObj->approveType; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
            }
            if ($memoType == 'management') {
                ?>
                <tr class='fitem'>
                    <td valign="top"><label id="manLabel">Employee :</label></td>
                    <td>
                        <table id="productTab" class="ui-state-default">
                            <thead>
                            <th width="10">SL</th>
                            <th>EMPLOYEE: </th>
                            <th width="100">APPROVAL TYPE</th>
                            </thead>
                            <tbody> 
                                <?php while ($TypeObj = fetch_object($TypeResult)) { ?>
                                    <tr>
                                        <td><?php echo++$proSL; ?></td> 
                                        <td><?php echo $TypeObj->CARD_NO . '->' . $TypeObj->FIRST_NAME . ' ' . $TypeObj->LAST_NAME . '(' . $TypeObj->DESIGNATION_NAME . ')'; ?></td>
                                        <td><?php echo $TypeObj->approveType; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            <?php } ?>
            <tr class='fitem'>
                <td>MEMO DATE :</td>
                <td><?php echo bddate($memoDate); ?></td>
            </tr>


            <tr class='fitem'>
                <td valign="top">Cost Center :</td>
                <td>
                    <table class="ui-state-default" id="cost_center" >
                        <thead>
                        <th width="30">SL</th>
                        <th>CC Code/ Division/ Office</th>                        
                        </thead>
                        <tbody>
                            <?php
                            $slNo = 0;
                            $divSQL = "SELECT cc.COST_CENTER_CODE,cc.COST_CENTER_NAME, dn.DIVISION_NAME, dd.memo_management_id
                            FROM mem_man_div_details dd
                            LEFT JOIN cost_center cc ON cc.cost_center_id =dd.cost_center_id
                            LEFT JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID

                            WHERE dd.memo_management_id='$searchID'";
                            //$db->OpenDb();
                            $resultSQL = query($divSQL);
                            //$db->CloseDb();
                            while ($objDiv = fetch_object($resultSQL)) {
                                ?>
                                <tr>
                                    <td><?php echo++$slNo; ?></td>
                                    <td><?php echo $objDiv->COST_CENTER_CODE . '->' . $objDiv->COST_CENTER_NAME . '->' . $objDiv->DIVISION_NAME; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>


            <tr class='fitem'>
                <td>MEMO CATEGORY :</td>
                <td><?php echo $memoCategory; ?></td>
            </tr>


            <tr class='fitem'>
                <td>DETAILS :</td>
                <td><?php echo $memoDetails; ?></td>
            </tr>
            <tr class='fitem'>
                <td>APPROVED AMOUNT :</td>
                <td><?php echo $approveAmount; ?></td>
            </tr>
            <tr class='fitem'>
                <td>REMARKS :</td>
                <td><?php echo $remarks; ?></td>
            </tr>

            <tr class='fitem'>
                <td>PAYMENT METHOD:</td>
                <td><?php echo $paymentMethod; ?></td>
            </tr>

        </table>
        <?php file_upload_view($searchID, "memo_archive") ?>

        <a href="index.php" class="button">Memo List</a>
    </form>
</div>


<?php
include '../body/footer.php';
?>