<?php
include '../lib/DbManager.php';
//include("RequisitionDAL.php");

$db = new DbManager();

$search_id = getParam('search_id');

$sql = "SELECT REQUISITION_NO, REQUISITION_DATE, rq.PROCESS_DEPT_ID, SPECIFICATION, 
JUSTIFICATION, REMARK, e.FIRST_NAME, e.CARD_NO, rq.REQUISITION_TYPE_ID,
pd.PROCESS_DEPT_NAME, rt.REQUISITION_TYPE_NAME

FROM requisition rq
LEFT JOIN employee e ON e.CARD_NO=rq.CREATED_BY 
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
require_once "../public/phpuploader/include_phpuploader.php";
include("../body/header.php");

$uploader = new PhpUploader();

$uploader->MultipleFilesUpload = false;
$uploader->InsertText = "Upload File (Max 10M)";

$uploader->MaxSizeKB = 1024000;
$uploader->AllowedFileExtensions = "jpeg,jpg,gif,png,zip,pdf,docx,xlsx,doc";

//Where'd the files go?
$uploader->SaveDirectory = "../documents/PR";
?>

<script type="text/javascript" src="include.js"></script>
<script src="requisition.js" type="text/javascript" ></script>

<div id="showResult"></div>


<div class="easyui-layout" style="width:100%; height:700px; margin: auto;">  
    <div title="Search Option" data-options="region:'north',split:true, collapsed:true" title="West" style="height: 200px;">  
        <div class="easyui-accordion" data-options="fit:true,border:false"></div>  
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

    <div Title='Requisition Edit' data-options="region:'center',iconCls:'icon-ok'">  

        <form action="" method="POST" name='requisition' id='requisition' class="form" autocomplete="off">
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
                    <tbody>
                        <?php
                        $j = 1;

                        while ($rowAttachment = fetch_object($ResultAttachment)) {
                            ?>
                            <tr>
                                <td><?php echo $j; ?>.</td>
                                <td><?php echo $rowAttachment->ATTACH_TITTLE; ?></td>
                                <td align="center"> 
                                    <a href='<?php echo $rowAttachment->ATTACH_FILE_PATH; ?>' class="fancybox">View </a> 
                                    <div class='remove float-right' id="<?php echo $rowAttachment->FILE_ATTACH_LIST_ID; ?>"><img src='../public/images/delete.png'/></div>
                                </td>
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
                <table>
                    <tr>
                        <td valign="top">Specification:</td>
                        <td><textarea name="specification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->SPECIFICATION; ?></textarea></td>
                        <td valign="top">Justification:</td>
                        <td><textarea name="justification" style="width: 200px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->JUSTIFICATION; ?></textarea></td> 
                    </tr>
                    <tr>
                        <td valign="top">Remark:</td>
                        <td colspan="3"><textarea name="remark" style="width: 500px;" class="easyui-validatebox" data-options="required:true"><?php echo $var->REMARK; ?></textarea></td>
                    </tr>                
                </table>
            </fieldset>
            <button type="button" name="save" value="SaveRequisition" class="button" onclick="updateRequisition();">Save</button>

        </form>

        <div id="attach" class="easyui-dialog display_none" style="margin:auto; width:700px; height:400px;padding:10px 20px"  closed="true" buttons="#dlg-buttons">
            <div>Attachment Tittle</div>
            <div><input name="textfield" type="text" size="60" id="AttachmentDetails" placeholder="Title" value=""/></div>
            <div><?php $uploader->Render(); ?></div>
            <button type="button" class="button" onclick="javascript:$('#dlg').dialog('close');">Close</button>
        </div>


    </div>
</div>


<?php include '../body/footer.php'; ?>
