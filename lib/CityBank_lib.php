<?php

function OrderNo($OrderId) {
    if (strlen($OrderId) == 1) {
        $OrderNo = date('Y') . '00000' . $OrderId;
    } elseif (strlen($OrderId) == 2) {
        $OrderNo = date('Y') . '0000' . $OrderId;
    } elseif (strlen($OrderId) == 3) {
        $OrderNo = date('Y') . '000' . $OrderId;
    } elseif (strlen($OrderId) == 4) {
        $OrderNo = date('Y') . '00' . $OrderId;
    } elseif (strlen($OrderId) == 5) {
        $OrderNo = date('Y') . '0' . $OrderId;
    } elseif (strlen($OrderId) == 6) {
        $OrderNo = date('Y') . $OrderId;
    }

    return $OrderNo;
}

function user_identity_with_designation($employee_id) {

    $rec = find("SELECT CONCAT(FIRST_NAME,' ',LAST_NAME) AS givenname, d.DESIGNATION_NAME
		 FROM employee e 
		 -- left join user u on u.employeeid   =  e.employeeid 	
		 LEFT JOIN designation d on d.DESIGNATION_ID  =  e.DESIGNATION_ID
		 WHERE EMPLOYEE_ID='$employee_id'");

    $user_identity = '<b>' . $rec->givenname . '</b>' . "<br /> " . $rec->DESIGNATION_NAME . '<br>';
    return $user_identity;
}

function user_location($employeeId) {

    $rec = find("SELECT d.DIVISION_NAME, bd.BRANCH_DEPT_NAME, ot.OFFICE_NAME
            FROM employee e 
            left join branch_dept bd on bd.BRANCH_DEPT_ID = e.BRANCH_DEPT_ID
            left join office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
            LEFT JOIN division d ON d.DIVISION_ID=e.DIVISION_ID
            where e.EMPLOYEE_ID='$employeeId'");


    $user_location = $rec->DIVISION_NAME . " -> " . $rec->BRANCH_DEPT_NAME;
    if (substr($user_location, -3) == ' > ') {
        $user_location = substr($user_location, 0, -3);
    }


    return $user_location;
}

function UpdateRequestStatus($RequestID, $ModifyBy) {
    $SqlUpdateRequest = "UPDATE request_list SET
             MODIFY_BY = '$ModifyBy',
             MODIFY_DATE = NOW(),
             REQUEST_STATUS = '1'
             WHERE REQUEST_LIST_ID = '$RequestID' ";

    return $update = query($SqlUpdateRequest);
}

function user_identity($user_name) {

    $rec = find("select FIRST_NAME, CARD_NO, LAST_NAME
		 from employee e 
		 left join master_user u on u.USER_NAME =  e.CARD_NO 	
		 where u.USER_NAME='$user_name'");

    $user_identity = $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . " ( " . $rec->CARD_NO . " ) ";
    return $user_identity;
}

function challan_no($challan_no) {
    list($y, $m, $d) = explode("-", date("Y-m-d"));
    $y_num = substr($y, 2, 2);
    $random = rand(3, 6);
    $challan_no = $y_num . "0000" . $challan_no;
    return $challan_no;
}

function evaluation_no($reco_no) {
    list($y, $m, $d) = explode("-", date("Y-m-d"));
    $y_num = substr($y, 2, 2);
    $reco_num = $y_num . "000" . $reco_no;
    return $reco_num;
}

function po_no($po_no) {
    list($y, $m, $d) = explode("-", date('Y-m-d'));
    $y_num = substr($y, 2, 2);
    $reco_num = $y_num . "000" . $po_no;
    return $reco_num;
}

function total_received_sr($requisitionId) {
    $order_update = query("SELECT si.REQUISITION_ID,
    sum(si.quantity) as quantities,
    sum(dh.deliverd) as deliverd,
    'fully_received' as product_status
    from (SELECT PRODUCT_ID, REQUISITION_ID, STATUS_APP_LEVEL,
    sum(si.QTY) as quantity
    from requisition_details si
    group by si.PRODUCT_ID, si.REQUISITION_ID) si
    left join (
    select req_id, product_id, sum(delivery_qty) as deliverd
    from app_product_delivery_history
    group by req_id, product_id
    ) dh on si.REQUISITION_ID=dh.req_id and si.PRODUCT_ID=dh.product_id
    group by si.REQUISITION_ID having min(si.status_app_level)=4 and sum(si.quantity)=sum(dh.deliverd)");
    while ($rec_so = fetch_object($order_update)) {
        sql("update requisition set REQUISITION_STATUS_ID=5 where REQUISITION_ID='$requisitionId'");
    }
}

function partly_received_requisition($requisitionId) {
    $order_update = query("SELECT si.REQUISITION_ID,
    sum(si.quantity) as quantities,
    sum(dh.deliverd) as deliverd,
    'partial_receipt' as product_status
    from 	(
    SELECT PRODUCT_ID, REQUISITION_ID, STATUS_APP_LEVEL,
    sum(si.QTY) as quantity
    from requisition_details si
    group by si.PRODUCT_ID, si.REQUISITION_ID
    ) si
    left join (
    select req_id, product_id, sum(delivery_qty) as deliverd
    from app_product_delivery_history
    group by req_id, product_id
    ) dh on si.REQUISITION_ID=dh.req_id and si.PRODUCT_ID=dh.product_id
    group by si.REQUISITION_ID
    having (min(si.STATUS_APP_LEVEL)=4 and sum(si.quantity) > sum(dh.deliverd))
    or (min(si.STATUS_APP_LEVEL) < 4 and max(si.status_app_level) >= 4)");

    while ($rec_so = fetch_object($order_update)) {
        sql("update requisition set REQUISITION_STATUS_ID=4 where REQUISITION_ID='$requisitionId'");
    }
}

function allocated($productid) {
    $value = findValue("select sum(delivery_qty) as allocated from app_product_delivery_history
		   where product_id='$productid' AND challan_id IS NULL group by product_id");
    return $value ? $value : 0;
}

function stock($productid) {
    $available = findValue("select sum(QTY) as stock from stockmove
	where PRODUCT_ID='$productid' group by PRODUCT_ID");
    return $available ? $available : 0;
}

function delivery($productid) {
    $value = findValue("select sum(delivery_qty) as delivery from app_product_delivery_history where product_id='$productid' and receipt_by=0 ");
    return $value;
}

function star_sign($priorityid) {
    $star_num = str_repeat("&#42;", $priorityid);
    $color = ($priorityid == 3) ? 'red' : 'black';
    $str_sign = "<font style='letter-spacing:3px; font-size:16px; font-weight:bold; color:$color'> $star_num </font>";
    return $str_sign;
}

function reco_no($reco_no) {
    $rec_date = findValue("select REQUISITION_DATE from requisition where REQUISITION_ID='$reco_no'");
    list($y, $m, $d) = explode("-", $rec_date);
    $y_num = substr($y, 2, 2);
    $reco_num = $y_num . "000" . $reco_no;
    return $reco_num;
}

function GetPriorityList() {
    return $priorityList = array(array(1, 'High'), array(2, 'Medium'), array(3, 'Normal'));
}

function getStatusList() {
    return $statusList = array(array(1, 'Draft'), array(1, 'New'), array(2, 'Waiting for Review'), array(3, 'Processing'), array(4, 'Waiting for received'), array(5, 'Partial Received'), array(6, 'Goods Received'), array(7, 'Cancelled'));
}

function getIsAssetList() {
    return $isAssetList = array(array('Yes', 'Yes'), array('No', 'No'));
}

function dateFormatForMysql($rawDate) {
    if ($rawDate) {
        $date = date('Y-m-d', strtotime($rawDate));
    } else {
        $date = '';
    }
    return $date;
}

function cs_status($val) {
    $array_id = array('', '1', '2', '3', '4', '5');
    $array_status = array('', 'New', 'Processing', 'Approved', 'Closed', 'Cancelled');

    $status = str_replace($array_id, $array_status, $val);
    return $status;
}

$cs_status_list = array(array('1', 'New'), array('2', 'Processing'), array('3', 'Approved'), array('4', 'Closed'), array('5', 'Cancelled'));

function getWorkFlowProcessList() {
    return rs2array(query("SELECT WORKFLOW_PROCESS_ID, WORKFLOW_PROCESS_NAME FROM workflow_process_type ORDER BY WORKFLOW_PROCESS_NAME"));
}

function deligationAdd() {
    $WorkFlowProcessList = getWorkFlowProcessList();
    ?>

    <table id="deligation" class="ui-state-default" width="100%">
        <thead>
        <th width="20"> SL.</th>
        <th width="200">Approve Type</th>
        <th width="150">Employee Card No</th>
        <th>Employee Details </th>
    </thead>
    <tbody>
        <tr>
            <td>1.</td>
            <td><?php comboBox('workFlowTypeId[]', $WorkFlowProcessList, '', TRUE, '', ''); ?></td>
            <td><input type="text" name="Card_no" onchange="EmployeeInfo($(this));" />
                <input type="hidden" name="employeeId[]" id="employee_id"/>
                <input type="hidden" name="designationId[]" id="designationId"/>
            </td>
            <td id="employee_details"></td>
        </tr>
    </tbody>
    </table>
    <button class="button" type="button" onClick="AddABoq('deligation');">Add More</button>  

    <?php
}

function deligationView($requisitionId, $module) {
    ?>

    <br/>
    <table class="ui-state-default">
        <thead>
        <th width="20" data-options="field:'1'">Ser</th>
        <th width="200" data-options="field:'2'">Work Flow</th>
        <th data-options="field:'4'">Employee Details</th>
    </thead>
    <tbody>
        <?php
        $deligationViewResult = deligationViewSql($requisitionId, $module);
        while ($row = fetch_object($deligationViewResult)) {
            ?>
            <tr>
                <td><?php echo++$i; ?></td>
                <td><?php echo $row->WORKFLOW_PROCESS_NAME; ?></td>
                <td><?php echo $row->empName; ?></td>
            </tr>
            <?php
        }
        ?>

    </tbody>
    </table>      
    <br/>
    <?php
}

function deligationViewSql($requisitionId, $module) {
    $sql = "SELECT sht.WORKFLOW_PROCESS_NAME, CONCAT(em.FIRST_NAME,' ',em.LAST_NAME, '->', em.CARD_NO,' (', d.DESIGNATION_NAME, ')') AS 'empName'
        FROM workflow_manager sh 
        LEFT JOIN workflow_process_type sht ON sht.WORKFLOW_PROCESS_ID = sh.WORKFLOW_PROCESS_ID
        LEFT JOIN employee em ON em.EMPLOYEE_ID = sh.EMPLOYEE_ID
        LEFT JOIN designation d ON d.DESIGNATION_ID = em.DESIGNATION_ID
        WHERE sh.REQUISITION_ID = '$requisitionId' and sh.MODULE_NAME ='$module'";
    $result = query($sql);
    return $result;
}

function GetEmployeeDetails($card_no) {
    $sql = "SELECT em.EMPLOYEE_ID, em.DESIGNATION_ID, CONCAT(em.FIRST_NAME,' ',em.LAST_NAME, '->', em.CARD_NO,' (', d.DESIGNATION_NAME, ')') AS 'empName'
    FROM employee em
    LEFT JOIN designation d ON d.DESIGNATION_ID = em.DESIGNATION_ID
    WHERE CARD_NO ='$card_no'";
    $res = find($sql);
    return json_encode($res);
}

function SaveWorkFlow($requisitionId, $module, $employeeId) {
    $workFlowTypeId = getParam('workFlowTypeId');
    $employee = getParam('employeeId');
    $designationId = getParam('designationId');

    foreach ($workFlowTypeId as $key => $value) {

        $sql = "INSERT INTO workflow_manager(REQUISITION_ID, MODULE_NAME, WORKFLOW_PROCESS_ID, EMPLOYEE_ID, DESIGNATION_ID, SL, APPROVE_STATUS, CREATED_BY, CREATED_DATE) 
            VALUES('$requisitionId','$module', '$value', '$employee[$key]','$designationId[$key]','$key','0', '$employeeId',NOW())";
        sql($sql);
    }
}

function getEmpinfo($cardNo) {
    $sql = "SELECT * FROM employee WHERE CARD_NO='$cardNo'";
    $var = find($sql);
    return $var;
}

function user_identityById($employeeId) {

    $rec = findValue("SELECT CONCAT(CARD_NO,'->',FIRST_NAME, ' ', LAST_NAME) FROM employee e WHERE EMPLOYEE_ID='$employeeId'");

    return $rec;
}

function po_status($val) {

    if ($val <= 9) {
        $val = "0" . $val;
    }
    $array_id = array('', '01', '02', '03', '04', '05', '06');
    $array_status = array('', 'New', 'Finalized', 'Cancel', 'Deliverd to Supplier', 'Partly Goods Received', 'Goods Received');

    $status = str_replace($array_id, $array_status, $val);
    return $status;
}

function getRequisitionByProduct($ProcessDeptId, $productId) {
    $sql = "SELECT si.PRODUCT_ID,
                    si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
                    pr.PRODUCT_NAME, 
                    si.QTY as quantities,
                    si.UNIT_PRICE,
                    so.CREATED_BY,
                    dv.DIVISION_NAME, 
                    so.OFFICE_TYPE_ID, 
                    so.BRANCH_DEPT_ID,
                    so.REQUISITION_NO,
                    e.FIRST_NAME, e.LAST_NAME,
                    e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME,
                    APPROVE_QTY, si.DETAILS_STATUS, e.COST_CENTER_ID, bd.SOL_ID,
                    cos.COST_CENTER_NAME, s.SOL_NAME, sup.SUPPLIER_NAME, si.REF_DATE, si.BILL_NO


                    FROM requisition_details si
                    LEFT JOIN supplier sup ON sup.SUPPLIER_ID=si.SUPPLIER_ID
                    LEFT JOIN cost_center cos ON cos.COST_CENTER_ID=si.COST_CENTER_ID
                    left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                    left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
                    left join division dv on dv.DIVISION_ID=so.DIVISION_ID
                    LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
                    LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
                    LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                    LEFT JOIN sol s ON s.SOL_ID=bd.SOL_ID

                    WHERE so.REQUISITION_STATUS_ID=3 AND pr.PROCESS_DEPT_ID='$ProcessDeptId'
                    AND si.DETAILS_STATUS=15 AND si.STATUS_APP_LEVEL=2 AND si.PRODUCT_ID='$productId'
                    ORDER BY so.REQUISITION_ID DESC";
    $result = query($sql);
    return $result;
}
?>

