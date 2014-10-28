<?php
include_once '../lib/DbManager.php';
$searchID = getParam('id');
$db = new DbManager();
$db->OpenDb();
//$divList = rs2array(query("SELECT DIVISION_ID, DIVISION_NAME FROM division"));



$selectSQL = "SELECT MEMO_TYPE, MEMO_DATE, MEMO_INFO_REF, MEMO_REF, BOARD_NO, BOARD_DATE, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS,
    PAYMENT_METHOD, MEMO_SUBJECT, CREATED_BY, CREATED_DATE FROM memo_archive WHERE MEMO_ARCHIVE_ID='$searchID'";

$memoObj = find($selectSQL);

$db->CloseDb();

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
include ('./memoArchive.php');
include_once '../body/body_header.php';
?>

<script type="text/javascript">
    $(document).ready(function() {

        $('#boardtr').hide();
        $('#managetr').hide();

        $('#board').click(function() {
            $('#managetr').hide();
            $('#boardtr').show();
            //$('#manLabel').text('Board Info');
        });

        $('#manage').click(function() {
            $('#boardtr').hide();
            $('#managetr').show();
            $('#manLabel').text('Mngmnt Info');
        });

    });
</script>
<script type="text/javascript">
    function myformatter(date) {
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
    }
    function myparser(s) {
        if (!s)
            return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0], 10);
        var m = parseInt(ss[1], 10);
        var d = parseInt(ss[2], 10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
            return new Date(y, m - 1, d);
        } else {
            return new Date();
        }
    }
</script>
<script src="../public/uploadify/jquery.uploadify-3.1.min.js"></script>
<link href="../public/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../public/js/jquery.calculation.js"></script>


<link rel="stylesheet" type="text/css" href="../public/combogrid/css/smoothness/jquery.ui.combogrid.css">
<script type="text/javascript" src="../public/combogrid/plugin/jquery.ui.combogrid-1.6.2.js"></script>
<script src="Requisition.js"></script>






<div id="p" class="easyui-panel" title="Add Memo" style="width:1000px;height:500px;padding:10px;">  
    <form method="post" enctype="multipart/form-data" id="fm" novalidate autocomplete="off">
        <table class="table">
            <tr class='fitem'>                        
                <td width="120"><strong>MEMO INFO REF</strong></td>
                <td><?php echo $memoInfoRef; ?></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> SUBJECT :</strong></td>
                <td><?php echo $memoSub; ?></td>
            </tr>

            <tr class='fitem'>                        
                <td><strong> MEMO TYPE :</strong></td>
                <td colspan="5"><?php echo $memoType; ?></td>
            </tr>

            <tr class='fitem' id="boardtr">
                <td><strong>BOARD NO.</strong></td>
                <td><input type='text' name='BOARD_NO' data-options="required:true"></td>
                <td><strong>DATE:</strong></td>
                <td><input type='text' name="BOARD_DATE" class='easyui-datebox' data-options="formatter:myformatter,parser:myparser"></td>
            </tr>

            <tr id="managetr">
                <td><label id="manLabel"></label></td>
                <td colspan="4">
                    <table id="productTab" class="ui-state-default">
                        <thead>
                        <th width="10">SL</th>
                        <th>EMPLOYEE</th>
                        <th width="100">APPROVAL TYPE</th>
                        <th width="50">ACTION</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo++$proSL; ?></td> 
                                <td><input style="width:100%" name="price[]" type="text" class="price number" id="price_product" value="" /></td>
                                <td><select name="apprvType[]">
                                        <option value="Initiator">Initiator</option>
                                        <option value="Recommended">Recommended</option>
                                        <option value="Approved">Approved</option>
                                    </select>
                                </td>
                                <td><div class="remove">Remove</div></td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" title="productTab" onclick="RemoveTableTr('productTab');">Add</a>
                </td>
            </tr>

            <tr class='fitem'>
                <td><strong>MEMO DATE :</strong></td>
                <td><?php echo $memoDate; ?></td>
            </tr>

            <tr class='fitem'>
                <td><strong>MEMO REFERENCE :</strong></td>
                <td><?php echo $memoRef; ?></td>
            </tr>
            <tr class='fitem'>
                <td><strong>DIVISION :</strong></td>
                <td colspan="2">
                    <table class="ui-state-default" id="cost_center" >
                        <thead>
                        <th width="20">SL</th>
                        <th>Division</th>                        
                        </thead>
                        <tbody>
                            <?php 
                            $slNo=0;
                            /*
                            $divSQL="SELECT cc.COST_CENTER_CODE,  cc.COST_CENTER_NAME, dn.DIVISION_NAME FROM cost_center cc
                                    LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID";
                            
                             * 
                             */
                            $divSQL="SELECT CONCAT(cc.COST_CENTER_CODE,'-',cc.COST_CENTER_NAME,'-', dn.DIVISION_NAME) AS CcDiv FROM cost_center cc
                                LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID
                                LEFT OUTER JOIN mem_man_div_details dd ON dd.division = cc.COST_CENTER_CODE 
                                WHERE dd.memo_management_id='$searchID'";
                            $db->OpenDb();
                            $resultSQL= query ($divSQL);
                            $db->CloseDb();
                            while ($objDiv = fetch_object($resultSQL)){
                            ?>
                            <tr>
                                <td><?php echo ++$slNo;?></td>
                                <td><?php echo $objDiv->CcDiv; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>


            <tr class='fitem'>
                <td><strong>MEMO_CATEGORY :</strong></td>
                <td><?php echo $memoCategory; ?></td>
            </tr>



            <tr class='fitem'>
                <td><strong> DETAILS :</strong></td>
                <td><?php echo $memoDetails; ?></td>
            </tr>
            <tr class='fitem'>
                <td><strong>APPROVED_AMOUNT :</strong></td>
                <td><?php echo $approveAmount; ?></td>
            </tr>
            <tr class='fitem'>
                <td><strong>REMARKS :</strong></td>
                <td><?php echo $remarks; ?></td>
            </tr>

            <tr class='fitem'>
                <td><strong>PAYMENT_METHOD :</strong></td>
                <td><?php echo $paymentMethod; ?></td>
            </tr>
            <tr class='fitem'>
                <td>Attachment Tittle</td>
                <td colspan="4">
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <table class="ui-state-default" id="attachment_tab">
                        <thead>
                        <th width="20">SL</th>
                        <th align="left">Attachment Tittle</th>
                        <th width="100" align="right">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            $sl=0;
                            $fileSQL = "SELECT ATTACH_TITTLE, ATTACH_FILE_PATH FROM memo_file_attach_list WHERE REQUEST_ID='$searchID' AND MODULE_NAME='memo_archive'";
                            $db->OpenDb();
                            $fileResult = query($fileSQL);
                            $db->CloseDb();
                            while ($objInfo = fetch_object($fileResult)) {
                                ?>
                                <tr>
                                    <td><?php echo ++$sl; ?></td>
                                    <td><?php echo $objInfo->ATTACH_TITTLE; ?></td>
                                    <td align='center'><a href='<?php echo $objInfo->ATTACH_FILE_PATH; ?>'>view</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </td>
            </tr>

        </table>
        <input type="submit" name="save" value="Edit" id='save' class="button"> | <a href="index.php" class="button">Confirm</a>
    </form>
</div>

<?php
include '../body/footer.php';
?>