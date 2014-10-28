<?php
include_once '../lib/DbManager.php';
include '../body/header.php';
include ('../lib/pagination.php');

//$divList = rs2array(query("SELECT cc.COST_CENTER_CODE, cc.COST_CENTER_CODE, cc.COST_CENTER_NAME, dn.DIVISION_NAME FROM cost_center cc
//LEFT OUTER JOIN division dn ON dn.DIVISION_ID = cc.DIVISION_ID"));

//
//$memoDivs='';
//$approvalTypes='';
//$whereClause = " 1";

//if ($_POST) {
//    
//    
//   
//    //die();
//    
//    $memoType = getParam('memoType');
//    $memoFrom = getParam('memo_from');
//    $memoTo = getParam('memo_to');
//    $empID = getParam('emp_id');
//    $empID1 = getParam('emp_id1');
//    $div = getParam('division');
//    $approvalType= getParam('approvalType');
//    //echo $div;
//    
//    if ($div!=''){
//    $memoDivSQL="SELECT memo_management_id FROM mem_man_div_details WHERE division='$div'";
//    $memoDiv = query($memoDivSQL);
//    while($divResult = fetch_object($memoDiv)){
//        $memoDivs.= $divResult->memo_management_id.',';
//    }
//    
//    $memoDivArray = substr($memoDivs, 0,-1);
//    if($memoDivArray==''){
//        $memoDivArray=0;
//    }
//    $whereClause.=" AND MEMO_ARCHIVE_ID IN($memoDivArray)";
//       
//    }
//    
//    if ($approvalType!='' && $empID1!=''){
//    $empAndApproveTypeSQL="SELECT memo_archive_id FROM mem_manage_emp_det WHERE empID='$empID1' AND approveType='$approvalType'";
//    //die();
//    $aprvTypeRes = query($empAndApproveTypeSQL);
//    while($aprResult = fetch_object($aprvTypeRes)){
//        $aprvs.= $aprResult->memo_archive_id.',';
//    }
//    //echo $aprvs;
//    
//    $aprvArray = substr($aprvs, 0,-1);
//    if($aprvArray==''){
//        $aprvArray=0;
//    }
//    $whereClause.=" AND MEMO_ARCHIVE_ID IN($aprvArray)";
//     
//    }
//    
//    if ($memoType != '') {
//        $whereClause.= " AND MEMO_TYPE='$memoType'";
//    }
//    if ($empID != '') {
//        $whereClause.= " AND CREATED_BY='$empID'";
//    }
//    
//    if ($memoFrom != '' AND $memoTo != '') {
//        $whereClause.= " AND MEMO_DATE BETWEEN '$memoFrom' AND '$memoTo'";
//    }
//    //echo $whereClause;
//}

$memoTypeList = rs2array(query("SELECT memo_type, memo_type FROM memo_type"));
$apprTypeList=rs2array(query("SELECT approval_type_name, approval_type_name FROM approval_type"));



//$gridSQL = "SELECT MEMO_ARCHIVE_ID, MEMO_TYPE, MEMO_DATE, MEMO_REF, MEMO_SUBJECT,
//    MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS, PAYMENT_METHOD, MEMO_INFO_REF 
//    FROM memo_archive WHERE $whereClause";
//$resultQuery = query($gridSQL);


$gridSQL="SELECT
        '' AS sl,
	cb_no,
	transaction_date, 
	ga.GL_ACCOUNT_CODE,
	ga.GL_ACCOUNT_NAME,
	bill_amount,
	vat_amount,
	mushak_amount
FROM
	vat_11 v
LEFT OUTER JOIN gl_account ga ON ga.GL_ACCOUNT_ID = v.account_head";

$resultQuery = query($gridSQL);

?>

<script type="text/javascript">

    $(document).ready(function(){
    //alert('AA');
    $('#tt').datagrid({
    onDblClickRow: function(rowIndex, rowData)
    {
    window.location.href = 'nextPage.php?id=' + rowData.MEMO_ARCHIVE_ID; }
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

    function ddmmyyyyToDate(str) {
    var parts = str.split("-"); // Gives us ["dd", "mm", "yyyy"]
            return new Date(parseInt(parts[0], 10), // Year
            parseInt(parts[1], 10) - 1, // Month (starts with 0)
            parseInt(parts[2], 10)); // Day of month
    }

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
<title>Memo List | Memo Archive</title>

<br/><br/>

<div class="easyui-layout" style="width:100%; height:600px;">  
    
    <div data-options="region:'center'"> 

        <div class="easyui-accordion" data-options="fit:true,border:false,plain:true">  
            <div title="Memo List">       
                <div id="dlg"  style="padding:10px 20px" closed="true" buttons="#dlg-buttons">
                    <form  method="POST">
                        <fieldset>
                            <legend>Search</legend>
                            <form>
                                <table>
                                    <tr>
                                        <td width='150'>Memo From  :</td>
                                        <td width='200'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_from' value="<?php echo $memoFrom; ?>"></input></td>
                                        <td width='150'>Memo To  :</td>
                                        <td width='100'><input class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" name='memo_to' value="<?php echo $memoTo; ?>"></input></td>
                                    </tr>
                                    <tr>
                                        <td>Memo Created By:</td>
                                        <td><div id="cgi"><input id="cg" type='text' name='emp_id' class='easyui-validatebox' size="20" value="<?php echo $empID;?>"></div></td>
                                        <td>Memo Type  :</td>
                                        <td><?php comboBox('memoType', $memoTypeList, $memoType, TRUE); ?></td>
                                        <td>Division/CC Code: </td>
                                        <td><?php comboBox('division', $divList, $div, TRUE); ?></td>
                                    </tr>
                                     <tr>
                                        <td>Approval Person:</td>
                                        <td><div id="cgi"><input id="cg1" type='text' name='emp_id1' class='easyui-validatebox' size="20" value="<?php echo $empID1;?>"></div></td>
                                        <td>Approval Type  :</td>
                                        <td><?php comboBox('approvalType',$apprTypeList, $approvalType, TRUE);?></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </table>
                                <input type='submit' name='search' value='search' class="button">
                            </form>
                        </fieldset>
                    </form>
                    <br />
                    <br />
                    <table id="tt" class="easyui-datagrid" style="width:1150px;height:auto;" pagination="true">  
                        <thead>  
                            <tr>
                                <th field="sl" width="30">SL</th>  
                                <th field="cb_no" width="100">CB No.</th>  
                                <th field="transaction_date" width="100">Transaction Date</th> 
                                <th field="GL_ACCOUNT_CODE" width="100">Account Code</th>
                                <th field="GL_ACCOUNT_NAME" width="100">Account Head</th>
                                <th field="bill_amount" width="100">Bill Amount</th>  
                                <th field="vat_amount" width="100">VAT Amount</th>  
                                <th field="mushak_amount" width="150">Mushak 11 Amount</th>
                                <!-- <th field="LINK" width="150" align="center">ACTION</th> -->
                            </tr>                            
                        </thead>                             
                        <tbody>   
                            <?php
                            //SELECT MEMO_TYPE, MEMO_DATE, MEMO_REF, MEMO_DETAILS, MEMO_CATEGORY, APPROVED_AMOUNT, REMARKS, PAYMENT_METHOD, MEMO_INFO_REF  FROM memo_archive
                            while ($resultObj = fetch_object($resultQuery)) {
                                ?>
                                <tr>  
                                    <td><?php echo ++$sl; ?></td>
                                    <td><?php echo $resultObj->cb_no; ?></td>
                                    <td><?php echo $resultObj->transaction_date; ?></td>
                                    <td><?php echo $resultObj->GL_ACCOUNT_CODE; ?></td>
                                    <td><?php echo $resultObj->GL_ACCOUNT_NAME; ?></td>
                                    <td><?php echo $resultObj->bill_amount; ?></td>
                                    <td><?php echo $resultObj->vat_amount; ?></td>
                                    <td align="right"><?php echo $resultObj->mushak_amount; ?></td>
                                    
                                </tr>                            
                            <?php } ?>                                
                        </tbody>                             
                    </table> 

                </div>
            </div>          
        </div>  
    </div>  
</div>