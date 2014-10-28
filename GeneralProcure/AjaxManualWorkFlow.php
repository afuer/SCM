<?php
include_once '../lib/DbManager.php';
$WorkFlowProcessTypeList = $db->rs2array("SELECT WORKFLOW_PROCESS_ID,WORKFLOW_PROCESS_NAME  FROM workflow_process_type");
?>

<table class="ui-state-default"  id="WorkflowTab" >
    <thead>
    <th width="30">SL.</th>
    <th width="150">Work Flow Process</th>
    <th width="150">Card No</th>
    <th width="250">Employee Name</th>
    <th width="150">Designation</th>
    <th width="80">Action</th>
</thead>
<tbody>
    <?php
    if ($mode == 'edit') {
        $saveButton = "<input type='button' name='save' id='addFlow' value='Save' searchId='$SearchId'  class='button' onClick='WorkFlowSave($(this))'/>";

        while ($RowWorkFlow = fetch_object($ResultWorkFlow)) {
            ?>
            <tr>
                <td align="center" ><?php echo++$manual_sl . '.'; ?></td>
                <td><?php echo $RowWorkFlow->WORKFLOW_PROCESS_NAME; ?></td>
                <td><?php echo $RowWorkFlow->EMPLOYEE_ID; ?></td>
                <td><?php echo $RowWorkFlow->empMame; ?></td>
                <td><?php echo $RowWorkFlow->DESIGNATION_NAME; ?></td>
                <td><input type='button' class="remove button" id="<?php echo $RowWorkFlow->GP_REQUISITION_FLOW_LIST_ID; ?>" name='save' value='Remove'/></td>
            </tr>
            <?php
            $i++;
        }
    }
    ?>
    <tr>
        <td>1</td>
        <td><?php combobox('workflow_process[]', $WorkFlowProcessTypeList, '', true, 'required'); ?></td>                
        <td><input type="text" name="EmployeeId[]" id="cardno" class="cardno" onchange="EmpInfo($(this));" /> </td> 
        <td><label id="EmpName"></label></td>
        <td>
            <label id="DesignationName"></label>
            <input type="hidden" name="EmployeeDesignID[]" id="designation" class="designation" value=""/>
        </td>
        <td align='center'><button type='button' class='button' onClick='$(this).parent().parent().remove();' value="">Remove</button><?php echo $saveButton; ?></td>
    </tr>
</tbody>

</table>
<button type="button" class="button" title="productTab" onclick="RemoveTableTr('WorkflowTab');">Add More</button>


<script type="text/javascript">
            function WorkFlowSave(obj) {

                var itemrow, Workflow, CardNo, Designation;
                itemrow = obj.closest('tr');
                Workflow = itemrow.find("select[name='workflow_process[]']").val();
                CardNo = itemrow.find("input[name='EmployeeId[]']").val();
                Designation = itemrow.find("input[name='EmployeeDesignID[]']").val();
                SearchId = $('#addFlow').attr('searchId');

                console.log(SearchId);
                var RowItem = {
                    "Workflow": "",
                    "CardNo": "",
                    "Designation": "",
                    "RequisitionId": ""
                };


                RowItem.Workflow = Workflow;
                RowItem.CardNo = CardNo;
                RowItem.Designation = Designation;
                RowItem.RequisitionId = SearchId;

                var jsonstr = JSON.stringify(RowItem);

                $.ajax({
                    url: "AjaxWorkFlowAdd.php",
                    data: "data=" + jsonstr,
                    type: "GET",
                    contentType: "application/json",
                    dataType: "text",
                    success: function(data) {
                        //console.log(data);
                    }
                });





            }
</script>
