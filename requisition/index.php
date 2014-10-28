<?php
include_once '../lib/DbManager.php';

$RequisitionType = $db->rs2array("SELECT REQUISITION_TYPE_ID, REQUISITION_TYPE_NAME FROM requisition_type ORDER BY REQUISITION_TYPE_NAME");
$RequisitionStatus = $db->rs2array("SELECT requisition_status_id, status_name FROM requisition_status ORDER BY status_name");
$processDeptList = $db->rs2array("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept ORDER BY PROCESS_DEPT_NAME");

include '../body/header.php';
?>
<script type="text/javascript" src="include.js"></script>


<div id="tt" class="easyui-tabs" data-options="fit:true,border:false,plain:true">  

    <div title="Requisition List">
        <fieldset>
            <legend>Search</legend>
            <table style="width: 100%">
                <tr>
                    <td width='100'>Req No:</td>
                    <td><input type="text" name="ReqNo" id="ReqNo" class="ReqNo"/></td>
                    <td width='100'>Req Status:</td>
                    <td width='100'><?php comboBox('ReqStatus', $RequisitionStatus, NULL, TRUE); ?></td>
                </tr>
                <tr>
                    <td>Req Type:</td>
                    <td><?php comboBox('ReqType', $RequisitionType, NULL, TRUE); ?></td>
                    <td width='100'>Process Dept:</td>
                    <td><?php comboBox('ProcessDeptId', $processDeptList, NULL, TRUE); ?></td>
                </tr>
                <tr>
                    <td>From Date:</td>
                    <td><input type="text" name="FromDate" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                    <td>To Date:</td>
                    <td><input type="text" name="ToDate" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                </tr>
            </table>

            <button class="easyui-linkbutton button" onclick="doSearch();" iconCls="icon-search">Search</button>
            <button type="button" class="easyui-linkbutton button" iconCls="icon-search" onclick="loadWindow();">Rest</button>
        </fieldset>
        <!--<a class="button" href="requisition_new.php?requisition_type_id=1">New Requisition</a>-->
        <button class="easyui-linkbutton button" iconCls="icon-edit" plain="true" onclick="editMyRequisition();">Edit Requisition</button>
        <button class="easyui-linkbutton button" iconCls="icon-view" plain="true" onclick="viewMyRequisition();">View Requisition</button>

        <table class="" id="allRequisition" ></table> 
    </div> 

    <?php if ($UserLevelId > 1) { ?>
        <div title="Pending Approval List">
            <button class="easyui-linkbutton button" iconCls="icon-edit" plain="true" onclick="RequisitionApprove();">Review Requisition</button>
            <table class="" id="RequisitionApproval"></table> 
        </div> 
    <?php } ?>

</div>  



<?php include '../body/footer.php'; ?>