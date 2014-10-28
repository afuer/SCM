<?php
include_once '../lib/DbManager.php';
include '../body/header.php';


$StatusList = rs2array(query("SELECT REQUISITION_STATUS_ID, STATUS_NAME FROM requisition_status"));
$processsDepartmentList = rs2array(query("SELECT REQUISITION_ROUTE_ID, ROUTE_NAME FROM requisition_route"));
?>

<script type="text/javascript">
    var url;
    $(document).ready(function() {

        $('#dg').datagrid({
            title: 'My Requisition List',
            pagination: 'true',
            toolbar: "#toolbar",
            singleSelect: true,
            pageSize: 20,
            pagePosition: 'top',
            idField: 'REQUISITION_ID',
            showFooter: true,
            url: 'DisplayRequisitionList.php',
            columns: [[
                    {field: 'REQUISITION_NO', title: 'PR No', sortable: "true", align: 'center'},
                    {field: 'REQUISITION_DATE', title: 'Date', align: 'center'},
                    {field: 'Requisition_from', title: 'Requisition From', align: 'center'},
                    {field: 'PROCESS_DEPT_NAME', title: 'Processing Dept'},
                    {field: 'REQUISITION_TYPE_NAME', title: 'Req Tytpe'},
                    {field: 'PresentLocation', title: 'Present Location'},
                    {field: 'Status', title: 'Status', align: 'center'},
                    {field: 'action', title: 'Action', width: 130, align: 'center',
                        formatter: function(value, row, index) {
                            var e = '<a href="RequisitionDisplay.php?mode=view&search_id=' + row.PARENT_REQUISITION_ID + '" onclick="link(this)">View</a> | ';
                            var d = '<a href="RequisitionEdit.php?mode=view&search_id=' + row.PARENT_REQUISITION_ID + '" onclick="deleterow(this)">Edit</a> |';
                            var f = '<a href="#" onclick="deleterow(this)">Delete</a>';
                            return e + d + f;
                        }
                    }
                ]]

        });

    });

</script>

<div class="easyui-layout" style="margin: auto; height:900px;">  
    <div Title='Requisition List' data-options="region:'center'" style="background-color:white; padding: 10px 10px;"> 


        <fieldset>
            <legend>Search</legend>
            <table class="table">
                <tr>
                    <td width="10">PR No: </td>
                    <td width="200"><input type="text" name="PRNo" value="<?php echo $PRNo; ?>" placeholder="PR No"/></td>
                    <td colspan="2">
                        From Date: <input type="text" name="Date_start" class="date" value="<?php echo $Date_start; ?>" placeholder="From Date"/>
                        To Date: <input type="text" name="Date_end" class="date" value="<?php echo $Date_end; ?>" placeholder="To Date"/>
                    </td>
                </tr>
                <tr>
                    <td>Status: </td>
                    <td><?php comboBox('Request_status', $StatusList, $Request_status, TRUE, 'easyui-combobox'); ?></td>
                    <td  width="120">Processing Dept: </td>
                    <td><?php comboBox('processsDepartment', $processsDepartmentList, $processsDepartment, TRUE); ?></td>
                </tr>
            </table>
            <button class="easyui-linkbutton button" iconCls="icon-search">Search</button>  
        </fieldset>
        <table id="dg"></table>
    </div>
</div>

<?php include '../body/footer.php'; ?>
