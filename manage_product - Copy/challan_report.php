<?php
include '../lib/DbManager.php';
//checkPermission(29);


$perpage = 20;
$page = getParam('page');
$page = ($page == '') ? 0 : $page;
$page = $page * $perpage;




$sql = "select ch.delivery_info,
                ch.challanid,
                ch.challan_no,
                ot.OFFICE_NAME,
                so.branch_dept_id, 
                sum(chi.uniteprice*chi.quantity) as total,
                so.REQUISITION_NO, 
                CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
                cs.challan_status_name,
                CONCAT(emp.CARD_NO,'->', emp.FIRST_NAME,' ',emp.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName'
                
                from challan ch
                left join challan_item chi on chi.challanid = ch.challanid
                left join app_product_delivery_history app on app.challan_id = ch.challanid
                left join master_user mu on mu.USER_NAME = ch.createdby
                left join employee emp on emp.EMPLOYEE_ID = mu.EMPLOYEE_ID
                left join requisition so on so.REQUISITION_ID = app.req_id
                LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
                LEFT JOIN designation d ON d.DESIGNATION_ID=emp.DESIGNATION_ID
                LEFT JOIN challan_status cs ON cs.challan_status_id=ch.`status`
                where  (ch.status=1 OR ch.status=2) 
                group by chi.challanid
                ORDER BY ch.challanid desc";
$sql2 = $sql . " LIMIT $page,$perpage";

$RequisitionStatus = $db->rs2array("SELECT requisition_status_id, status_name FROM requisition_status ORDER BY status_name");
$RequisitionType = $db->rs2array("SELECT REQUISITION_TYPE_ID, REQUISITION_TYPE_NAME FROM requisition_type ORDER BY REQUISITION_TYPE_NAME");
$processDeptList = $db->rs2array("SELECT PROCESS_DEPT_ID, PROCESS_DEPT_NAME FROM process_dept ORDER BY PROCESS_DEPT_NAME");

include '../body/header.php';
?>


<div class="easyui-layout" style="width:1100px; margin: auto; height:550px;">  
    <div Title='Delivered Challan list' data-options="region:'center'"  style="background-color:white; "> 

        <table class="easyui-datagrid" id="example">
            <thead>
                <tr>
                    <th field="1">SL.</th>
                    <th field="2">Challan. No</th>
                    <th field="3">Requisition No</th>
                    <th field="4">Branch/Dept.Name</th>
                    <th field="5" align="left">Created By </th>
                    <th field="6" align="left">Courier Service </th>
                    <th field="7" align="right">Amount</th>
                    <th field="8">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $db->query($sql);
                $sub_total = "";
                while ($rec = fetch_object($query)) {
                    $sl++;
                    ?> 
                    <tr>
                        <td><?php echo $sl; ?></td>
                        <td align="left"><strong><a href='challan_details.php?challanid=<?php echo $rec->challanid; ?>&requisition=<?php echo $rec->REQUISITION_NO; ?>' target='_blank'><?php echo $rec->challan_no; ?></a></strong></td>
                        <td align="left"><?php echo $rec->REQUISITION_NO; ?></td>
                        <td align="left"> <?php echo $rec->branch_dept; ?></td>
                        <td align="left"><?php echo $rec->employeeName; ?></td>
                        <td align="left"><?php echo $rec->delivery_info; ?></td>
                        <td align="right"><?php echo formatMoney($rec->total); ?></td>
                        <td><?php echo $rec->challan_status_name; ?></td>
                    </tr>

                    <?php
                    $sub_total = $sub_total + $rec->total;
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><strong>Total</strong></td>
                    <td><strong><?php echo formatMoney($sub_total); ?></strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

    </div>

</div>  
</div>
</div>


<?php include("../body/footer.php"); ?>