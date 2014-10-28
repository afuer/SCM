<?php
include '../lib/DbManager.php';
include("../body/header.php");

$mode = getParam('mode');
$processDept = getParam('processDept');
$search_id = getParam('search_id');

$sql = "SELECT REQUISITION_NO, REQUISITION_DATE, rq.PROCESS_DEPT_ID, SPECIFICATION, 
JUSTIFICATION, REMARK, e.FIRST_NAME, e.LAST_NAME, e.CARD_NO, rt.REQUISITION_TYPE_NAME,
pd.PROCESS_DEPT_NAME
FROM requisition rq
LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
LEFT JOIN employee e ON e.CARD_NO=rq.CREATED_BY
LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
WHERE REQUISITION_ID='$search_id'";

$sql_details = "SELECT REQUISITION_DETAILS_ID, rd.PRODUCT_ID, p.PRODUCT_NAME, rd.QTY, UNIT_PRICE, 
USER_COMMENT, ut.UNIT_TYPE_NAME, p.PRODUCT_CODE

FROM requisition_details rd
LEFT JOIN product p ON p.PRODUCT_ID=rd.PRODUCT_ID
LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID WHERE REQUISITION_ID='$search_id'";

if ($_POST) {
    $db->sql("UPDATE requisition SET REQUISITION_STATUS_ID='1' WHERE REQUISITION_ID='$search_id'");
    echo "<script>location.replace('index.php');</script>";
}



$var = $db->find($sql);
$resulProduct = $db->query($sql_details);
$processDepName = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$processDept'");
?>

<link rel="stylesheet" type="text/css" href="../jquery-ui/jquery-ui-1.8.23.custom_smoothness/css/smoothness/jquery-ui-1.8.23.custom.css">
<script type='text/javascript' src='../jquery-ui/jquery-ui-1.8.23.custom_smoothness/js/jquery-ui-1.8.23.custom.min.js'></script>

<input type="hidden" name="mode" value="<?php echo $mode ?>" />
<input type="hidden" name="search_id" value="<?php echo $search_id ?>" />


<div class="easyui-layout" style="width:100%; height:700px; margin: auto;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">
            DD

        </div>  
    </div>

    <div data-options="region:'south',split:true" style="height:50px;">Button Part</div>

    <div data-options="region:'east', split:true, collapsed:true" title="East" style="width:250px;">  
        <ul class="easyui-tree" data-options="url:'', animate:true, dnd:true"></ul>  
    </div> 

    <div data-options="region:'west',split:true, collapsed:true" title="West" style="width:200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false">  
            <div title="Title1" style="padding:10px;">  
                content1  
            </div>  
            <div title="Title2" data-options="selected:true" style="padding:10px;">  
                content2  
            </div>  
            <div title="Title3" style="padding:10px">  
                content3  
            </div>  
        </div>  
    </div>

    <div Title='Requisition View' data-options="region:'center',iconCls:'icon-ok'">  

        <table class="table" style="width: 800px;">
            <tr>
                <td width="120">PR No :  </td>
                <td width="200"><?php echo $var->REQUISITION_NO; ?></td >
                <td width="120">Staff Member :</td>
                <td><?php echo $var->FIRST_NAME . ' ' . $var->LAST_NAME . '(' . $var->CARD_NO . ')'; ?></td>
            </tr>
            <tr>
                <td>Requisition Date :</td>
                <td><?php echo bddate($var->REQUISITION_DATE); ?></td>
                <td>Location :</td>
                <td><?php echo user_location($var->CARD_NO); ?></td>
            </tr>
            <tr>
                <td>Created by :</td>
                <td><?php echo $var->CARD_NO; ?></td>
                <td>Process Dept : </td>
                <td><?php echo $var->REQUISITION_TYPE_NAME . '->' . $var->PROCESS_DEPT_NAME; ?></td>
            </tr>                    
        </table>
        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Add Product</legend>
            <table class="ui-state-default" style="width: 780px;">
                <thead>
                <th width="20">SL</th>
                <th>Product</th>
                <th width="130">Qty</th>
                <th width="80">Price</th>
                <th width="80">Total</th>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;

                    while ($row = fetch_object($resulProduct)) {
                        $grandTotal+=($row->QTY * $row->UNIT_PRICE);
                        $unit = $row->UNIT_PRICE > 0 ? formatMoney($row->UNIT_PRICE) : 'N/A';
                        $total = ($row->QTY * $row->UNIT_PRICE) > 0 ? formatMoney($row->QTY * $row->UNIT_PRICE) : 'N/A';
                        ?>
                    <tr style="font-weight: bold;">
                            <td><?php echo $sl; ?>.</td>
                            <td><?php echo $row->PRODUCT_NAME; ?></td>
                            <td align='center'><?php echo $row->QTY; ?></td>
                            <td align='right'><?php echo $unit; ?></td>
                            <td id="TotalPrice" align='right'><?php echo $total; ?></td>
                        </tr>
                        <?php
                        $sl++;
                    }
                    $grandTotal = $grandTotal > 0 ? formatMoney($grandTotal) : 'N/A';
                    ?>
                </tbody>
                <tfoot>
                <th></th>
                <th colspan="3" align="right">Grand Total</th>
                <th align="right"><div id="ProductGrantTotal"><?php echo $grandTotal; ?></div></th>
                </tfoot>
            </table>
        </fieldset>
        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Attachment Title</legend>
            <table class="ui-state-default" id="attachment_tab" style="width: 780px;">
                <thead>
                <th width="20">SL</th>
                <th align="left">Attachment Tittle</th>
                <th width="100" align="right">Action</th>
                </thead>
                <tbody>
                    <?php
                    $j = 1;
                    $ResultAttachment = attachResult($search_id, 'requisition');
                    while ($rowAttachment = fetch_object($ResultAttachment)) {
                        ?>
                        <tr>
                            <td><?php echo $j; ?>.</td>
                            <td><?php echo $rowAttachment->ATTACH_TITTLE; ?></td>
                            <td align="center"><a href='../documents/PR/<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' class="fancybox">View </a></td>
                        </tr>

                        <?php
                        $j++;
                    }
                    ?>
                </tbody>
            </table>
        </fieldset>
        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Comments</legend>
            <table class="table">
                <tr>
                    <td valign='top' width="150">Specification:</td>
                    <td ><?php echo $var->SPECIFICATION; ?></td>
                </tr>
                <tr>
                    <td valign='top'>Justification:</td>
                    <td><?php echo $var->JUSTIFICATION; ?></td> 
                </tr>
                <tr>
                    <td valign='top'>Remark:</td>
                    <td><?php echo $var->REMARK; ?></td>
                </tr>                
            </table>
        </fieldset>
        <br/>
        <?php if ($mode == 'cinfirm') { ?>
            <form class="" action="" method="POST">
                <a  href="../requisition/requisition_edit.php?mode=update&search_id=<?php echo $search_id; ?>" class="button">Edit</a>
                <button type="submit" class="button" name="submit">Confirm</button>
                <a href="index.php" class="button">Draft</a>
            </form>
            <?php
        }

        if ($mode == 'view') {
            include './ApprovalHistory.php';
            echo "<a href='index.php' class='button'>Requisition List</a>";
        }
        ?>


    </div>
</div>



<?php include '../body/footer.php'; ?>
