<?php
include '../lib/DbManager.php';


$requisition_type_id = getParam('requisition_type_id');
$processDeptId = getParam('processDeptId');
//$search_id = getParam('search_id');
//$search_id = $search_id == '' ? $userName : $search_id;
$maxRequisitionId = NextId('requisition', 'REQUISITION_ID');

$sql = "SELECT FIRST_NAME, LAST_NAME FROM employee e WHERE EMPLOYEE_ID='$user_name'";
$RquisitionType = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$requisition_type_id'");
$processDept = $db->findValue("SELECT PROCESS_DEPT_NAME FROM process_dept WHERE PROCESS_DEPT_ID='$processDeptId'");

$requisitionId = NextId('requisition', 'requisition_id');
$var = find($sql);
$requisitionForName = $db->findValue("SELECT REQUISITION_TYPE_NAME FROM requisition_type WHERE REQUISITION_TYPE_ID='$requisitionFor'");

$processDeptList = $db->rs2array(("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept ORDER BY PROCESS_DEPT_NAME"));
$ResultAttachment = attachResult($search_id, 'requisition');

require_once "../public/phpuploader/include_phpuploader.php";




include("../body/header.php");

$uploader = new PhpUploader();

$uploader->MultipleFilesUpload = false;
$uploader->InsertText = "Upload File (Max 10M)";

$uploader->MaxSizeKB = 1024000;
$uploader->AllowedFileExtensions = "jpeg,jpg,gif,png,zip,pdf,docx,xlsx,doc";

//Where'd the files go?
$uploader->SaveDirectory = "../documents/PR";
//echo "<pre>";
//print_r($_POST);
?>

<script src = "include.js" type = "text/javascript" ></script>
<script src="requisition_new.js" type="text/javascript" ></script>



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

    <div Title='Requisition New' data-options="region:'center',iconCls:'icon-ok'">  

        <form action="" method="POST" name='requisition' id='requisition' autocomplete="off">
            <input type="hidden" name="requisitionId" id="requisitionId" value="<?php echo $maxRequisitionId ?>" />
            <input type="hidden" name="mode" value="<?php echo $mode ?>" />
            <input type="hidden" name="search_id" value="<?php echo $search_id ?>" />
            <input type="hidden" name="requisition_type_id" id="requisition_type_id" value="<?php echo $requisition_type_id; ?>"/>
            <input type="hidden" name="processDeptId" id="processDeptId" value="<?php echo $processDeptId; ?>"/>

            <?php include './ajax_requisition_header.php'; ?>
            <br/>
            <fieldset class="fieldset" style="width: 780px;"> 
                <legend>Add Product</legend>
                <table id="productGrid" class="ui-state-default">
                    <thead>
                    <th>SL</th>
                    <th>Product</th>
                    <th width="150">Qty</th>
                    <th width="80">Action</th>
                    </thead>
                    <tbody></tbody>
                </table>
                <button type="button" class="button" id="Add" title="productTab" onclick="addCombo();">Add More</button>
                <a href="../product/product_get_list" class="button float-right" target="_blank">Show Product List</a>
            </fieldset>
            <br/>

            <fieldset class="fieldset" style="width: 780px;"> 
                <legend>Attachment Title</legend>
                <div><div class="float-left" style="color: blue; font-size: 12px; font-weight: bold; padding-right: 50px; padding-top: 10px;">Please Click Attach Button For Attach File</div> 
                    <button type="button"  class="button" onclick="attach();">Attach</button></div>

                <table class="ui-state-default" id="attachment_tab" style="width: 780px;">
                    <thead>
                    <th width="20">SL</th>
                    <th align="left">Attachment Tittle</th>
                    <th width="100" align="right">Action</th>
                    </thead>
                    <tbody></tbody>

                </table>
            </fieldset>
            <br/>
            <fieldset class="fieldset" style="width: 780px;"> 
                <legend>Comments</legend>
                <table>
                    <tr>
                        <td valign="top">Specification:</td>
                        <td><textarea name="specification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>
                        <td valign="top">Justification:</td>
                        <td><textarea name="justification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"></textarea></td>       
                    </tr>
                    <tr>
                        <td valign="top">Remark:</td>
                        <td colspan="3"><textarea name="remark" style="width: 500px;" class="easyui-validatebox" data-options="required:true"></textarea></td>
                    </tr>                
                </table>
            </fieldset>
            <button type="button" name="save" value="SaveRequisition" class="button" onclick="saveRequisition();">Save</button>
        </form>


        <div id="dlg" class="easyui-dialog display_none" style="margin:auto; width:700px; height:400px;padding:10px 20px"  closed="true" buttons="#dlg-buttons">
            <div>Attachment Tittle</div>
            <div><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title" value=""/></div>
            <div><?php $uploader->Render(); ?></div>
            <button type="button" class="button" onclick="javascript:$('#dlg').dialog('close');">Close</button>
        </div>

    </div>
</div>


<?php include '../body/footer.php'; ?>
