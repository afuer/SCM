<?php
include '../lib/DbManager.php';
//include("RequisitionDAL.php");
include '../body/header.php';


$search_id = getParam('search_id');

$sql = "SELECT REQUISITION_NO, REQUISITION_DATE, rq.PROCESS_DEPT_ID, SPECIFICATION, 
JUSTIFICATION, REMARK, e.FIRST_NAME, e.CARD_NO, rq.REQUISITION_TYPE_ID,
pd.PROCESS_DEPT_NAME, rt.REQUISITION_TYPE_NAME, FREE_TEXT, HELP_DESK

FROM requisition rq
LEFT JOIN employee e ON e.EMPLOYEE_ID=rq.CREATED_BY 
LEFT JOIN process_dept pd ON pd.PROCESS_DEPT_ID=rq.PROCESS_DEPT_ID
LEFT JOIN requisition_type rt ON rt.REQUISITION_TYPE_ID=rq.REQUISITION_TYPE_ID
WHERE rq.REQUISITION_ID='$search_id'";

$sql_details = "SELECT REQUISITION_DETAILS_ID, rd.PRODUCT_ID, p.PRODUCT_NAME, rd.QTY, UNIT_PRICE, 
USER_COMMENT, ut.UNIT_TYPE_NAME, p.PRODUCT_CODE

FROM requisition_details rd
LEFT JOIN product p ON p.PRODUCT_ID=rd.PRODUCT_ID
LEFT JOIN unit_type ut ON ut.UNIT_TYPE_ID=p.UNIT_TYPE_ID WHERE REQUISITION_ID='$search_id'";
$ResultAttachment = attachResult($search_id, 'requisition');


$var = find($sql);
$resulProduct = query($sql_details);
?>

<script type="text/javascript" src="include.js"></script>
<script src="requisition.js" type="text/javascript" ></script>


<div Title='Requisition Edit' class="easyui-panel" style="width:1000px; height:1500px;" > 

    <form action="" method="POST" name='requisition' id='requisition' class="form" autocomplete="off" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="<?php echo $mode ?>" />
        <input type="hidden" id="search_id" name="search_id" value="<?php echo $search_id ?>" />
        <input type="hidden" name="processDeptId" id="processDeptId" value="<?php echo $var->PROCESS_DEPT_ID; ?>"/>
        <input type="hidden" name="requisition_type_id" id="requisition_type_id" value="<?php echo $var->REQUISITION_TYPE_ID; ?>"/>

        <table class="table" style="width: 800px;">
            <tr>
                <td width="120">PR No :  </td>
                <td width="200"><?php echo $var->REQUISITION_NO; ?></td >
                <td width="120">Staff Member :</td>
                <td><?php echo $var->FIRST_NAME . '(' . $var->CARD_NO . ')'; ?></td>
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
            <table id="productGrid" class="ui-state-default" style="width: 780px;">
                <thead>
                <th width="20">SL</th>
                <th>Product Name</th>
                <th width="80">Qty</th>
                <th width="80">Action</th>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    while ($row = fetch_object($resulProduct)) {
                        ?>
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><?php echo $row->PRODUCT_NAME; ?></td>
                            <td align="right"><?php echo $row->QTY; ?></td>
                            <td align='center'><div class="remove" id="<?php echo $row->REQUISITION_DETAILS_ID; ?>" ><img src='../public/images/delete.png'/></div></td>
                        </tr>
                        <?php
                        $sl++;
                    }
                    $grandTotal = $grandTotal > 0 ? formatMoney($grandTotal) : 'N/A';
                    ?>
                </tbody>
            </table>
            <button type="button" class="button" id="Add" title="productTab" onclick="addCombo();">Add More</button>
            <a href="../product/product_get_list" class="button float-right" target="_blank">Show Product List</a>
        </fieldset>
        <br/>
        <?php file_upload_edit($search_id, "requisition", TRUE); ?>
        <br/>
        <fieldset class="fieldset" style="width: 780px;"> 
            <legend>Comments</legend>
            <table>
                <tr>
                    <td valign="top">Specification:</td>
                    <td><textarea name="specification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->SPECIFICATION; ?></textarea></td>
                    <td valign="top">Justification:</td>
                    <td><textarea name="justification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->JUSTIFICATION; ?></textarea></td> 
                </tr>
                <tr>
                    <td valign="top">On The behalf Off:</td>
                    <td><textarea name="freeText" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->FREE_TEXT; ?></textarea></td>
                    <td valign="top">Help Desk No:</td>
                    <td><textarea name="helpDesk" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->HELP_DESK; ?></textarea></td>       
                </tr>
                <tr>
                    <td valign="top">Remark:</td>
                    <td colspan="3"><textarea name="remark" style="width: 500px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->REMARK; ?></textarea></td>
                </tr>                
            </table>
        </fieldset>
        <button type="button" name="save" value="SaveRequisition" class="button" onclick="updateRequisition();">Save</button>

    </form>


</div>


<?php include '../body/footer.php'; ?>
