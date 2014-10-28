<?php
include_once '../lib/DbManager.php';
include '../body/header.php';

$divList = rs2array(query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME FROM cost_center cc
LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID"));


$memoDivs = '';
$approvalTypes = '';
$whereClause = " 1";

if ($_POST) {



    //die();

    $memoType = getParam('memoType');
    $memoFrom = getParam('memo_from');
    $memoTo = getParam('memo_to');
    $empID = getParam('emp_id');
    $empID1 = getParam('emp_id1');
    $div = getParam('division');
    $approvalType = getParam('approvalType');
    //echo $div;

    if ($div != '') {
        $memoDivSQL = "SELECT memo_management_id FROM mem_man_div_details WHERE division='$div'";
        $memoDiv = query($memoDivSQL);
        while ($divResult = fetch_object($memoDiv)) {
            $memoDivs.= $divResult->memo_management_id . ',';
        }

        $memoDivArray = substr($memoDivs, 0, -1);
        if ($memoDivArray == '') {
            $memoDivArray = 0;
        }
        $whereClause.=" AND MEMO_ARCHIVE_ID IN($memoDivArray)";
    }

    if ($approvalType != '' && $empID1 != '') {
        $empAndApproveTypeSQL = "SELECT memo_archive_id FROM mem_manage_emp_det WHERE empID='$empID1' AND approveType='$approvalType'";
        //die();
        $aprvTypeRes = query($empAndApproveTypeSQL);
        while ($aprResult = fetch_object($aprvTypeRes)) {
            $aprvs.= $aprResult->memo_archive_id . ',';
        }
        //echo $aprvs;

        $aprvArray = substr($aprvs, 0, -1);
        if ($aprvArray == '') {
            $aprvArray = 0;
        }
        $whereClause.=" AND MEMO_ARCHIVE_ID IN($aprvArray)";
    }

    if ($memoType != '') {
        $whereClause.= " AND MEMO_TYPE='$memoType'";
    }
    if ($empID != '') {
        $whereClause.= " AND CREATED_BY='$empID'";
    }

    if ($memoFrom != '' AND $memoTo != '') {
        $whereClause.= " AND MEMO_DATE BETWEEN '$memoFrom' AND '$memoTo'";
    }
    //echo $whereClause;
}

$memoTypeList = rs2array(query("SELECT memo_type, memo_type FROM memo_type"));
$apprTypeList = rs2array(query("SELECT approval_type_name, approval_type_name FROM approval_type"));



$gridSQL = "SELECT MEMO_ARCHIVE_ID, MEMO_TYPE, MEMO_DATE, MEMO_REF, MEMO_SUBJECT, MEMO_REF_NO,
    MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS, PAYMENT_METHOD, MEMO_INFO_REF 
    FROM memo_archive WHERE $whereClause
        ORDER BY MEMO_ARCHIVE_ID DESC";
$resultQuery = query($gridSQL);
?>

<script type="text/javascript">

    $(document).ready(function() {
        //alert('AA');
        $('#tt').datagrid({
            onDblClickRow: function(rowIndex, rowData)
            {
                window.location.href = 'nextPage.php?id=' + rowData.MEMO_ARCHIVE_ID;
            }
        });

        $('#cg').combogrid({
            panelWidth: 500,
            url: 'getdataForCombo.php',
            idField: 'EMPLOYEE_ID',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'EMPLOYEE_ID', title: 'Employee ID', width: 20},
                    {field: 'CARD_NO', title: 'Card No', align: 'right', width: 20},
                    {field: 'FIRST_NAME', title: 'Employee Name', align: 'left', width: 40}
                ]]
        });


        $('#cg1').combogrid({
            panelWidth: 500,
            url: 'getdataForCombo.php',
            idField: 'EMPLOYEE_ID',
            textField: 'EM',
            mode: 'remote',
            fitColumns: true,
            columns: [[
                    {field: 'EMPLOYEE_ID', title: 'Employee ID', width: 20},
                    {field: 'CARD_NO', title: 'Card No', align: 'right', width: 20},
                    {field: 'FIRST_NAME', title: 'Employee Name', align: 'left', width: 40}
                ]]
        });


    });


    function editUser222() {
        var row = $('#dg').datagrid('getSelected');
        if (row) {
            window.location.href = 'nextPage.php?id=' + row.MEMO_ARCHIVE_ID;
            //alert('row.MEMO_ARCHIVE_ID');
        }
    }


    function removeUser() {
        var row = $('#tt').datagrid('getSelected');
        if (row) {
            window.location.href = 'RoughPurpose_view.php?id=' + row.MEMO_ARCHIVE_ID;
            //alert('row.MEMO_ARCHIVE_ID');
        }
    }

    function newUser() {
        window.location.href = 'RoughPurpose.php';
    }

    function editEmployeeInfo() {
        var row = $('#tt').datagrid('getSelected');
        if (row) {
            //alert(row.MEMO_ARCHIVE_ID);
            window.location.href = 'nextPage.php?id=' + row.MEMO_ARCHIVE_ID;
        }
    }
</script>  

<br/><br/>
<div class="panel-header">Memo List</div>
<div style="padding: 20px 20px; background: white;">
    <form  method="GET">
        <fieldset>
            <legend>Search</legend>
            <table class="table">
                <tr>
                    <td width='150'>Memo From  :</td>
                    <td ><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_from' value="<?php echo $memoFrom; ?>"></input></td>
                    <td width='120'>Memo To  :</td>
                    <td><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_to' value="<?php echo $memoTo; ?>"></input></td>
                </tr>
                <tr>
                    <td>Memo Created By:</td>
                    <td><div id="cgi"><input id="cg" type='text' name='emp_id' class='easyui-validatebox' size="20" value="<?php echo $empID; ?>"></div></td>
                    <td>Memo Type  :</td>
                    <td><?php comboBox('memoType', $memoTypeList, $memoType, TRUE); ?></td>
                </tr>
                <tr>
                    <td>Approval Person:</td>
                    <td><div id="cgi"><input id="cg1" type='text' name='emp_id1' class='easyui-validatebox' size="20" value="<?php echo $empID1; ?>"></div></td>
                    <td>Approval Type  :</td>
                    <td><?php comboBox('approvalType', $apprTypeList, $approvalType, TRUE); ?></td>
                </tr>
                <tr>
                    <td>Division/CC Code: </td>
                    <td><?php comboBox('division', $divList, $div, TRUE); ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <input type='submit' name='search' value='search' class="button">
        </fieldset>
    </form>

    <a href="RoughPurpose.php" class="button" iconCls="icon-add" plain="true">Add Memo</a>
    <table class="ui-state-default">  
        <thead>  
            <tr>  
                <th field="MEMO_ARCHIVE_ID" width="30">SL.</th>  
                <th field="MEMO_REF" width="100">MEMO REF</th> 
                <th field="MEMO_SUBJECT" width="100">MEMO SUBJECT</th>
                <th field="MEMO_TYPE" width="100">MEMO TYPE</th> 
                <th field="MEMO_REF" width="100">MEMO REF</th> 
                <th field="MEMO_DATE" width="100">MEMO DATE</th>  
                <th field="MEMO_DETAILS" width="150">MEMO DETAILS</th>
                <th field="MEMO_CATEGORY" width="100">MEMO CATEGORY</th>
                <th field="APPROVED_AMOUNT" width="100">APPROVED AMOUNT</th>  
                <th field="PAYMENT_METHOD" width="100">PAYMENT METHOD</th>  
                <th field="MEMO_INFO_REF" width="100">MEMO INFO REF</th>
                <th colspan="2" field="LINK" width="150" align="center">ACTION</th> 
            </tr>                            
        </thead>                             
        <tbody>   
            <?php
            while ($resultObj = fetch_object($resultQuery)) {
                ?>
                <tr>  
                    <td><?php echo++$sl; ?></td>
                    <td><?php echo $resultObj->MEMO_REF; ?></td>
                    <td><?php echo $resultObj->MEMO_SUBJECT; ?></td>
                    <td><?php echo $resultObj->MEMO_TYPE; ?></td>
                    <td><?php echo $resultObj->MEMO_REF_NO; ?></td>
                    <td><?php echo bddate($resultObj->MEMO_DATE); ?></td>
                    <td><?php echo $resultObj->MEMO_DETAILS; ?></td>
                    <td><?php echo $resultObj->MEMO_CATEGORY; ?></td>
                    <td align="right"><?php echo $resultObj->APPROVED_AMOUNT; ?></td>
                    <td><?php echo $resultObj->PAYMENT_METHOD; ?></td>
                    <td><?php echo $resultObj->MEMO_INFO_REF; ?></td>
                    <td align="center"><a href="nextPage.php?id=<?php echo $resultObj->MEMO_ARCHIVE_ID; ?>" target="_blank">Transaction</a></td> 
                    <td align="center"><a href="RoughPurpose_view.php?id=<?php echo $resultObj->MEMO_ARCHIVE_ID; ?>"  target="_blank">View</a></td> 
                </tr>                            
            <?php } ?> 
        </tbody>                             
    </table> 

</div>

<?php
include '../body/footer.php';
?>

