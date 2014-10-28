<?php
include '../lib/DbManager.php';
include '../body/header.php';

//checkPermission(29);


$fromDate = getParam('fromDate');
$fromDate = $fromDate == '' ? firstDayMonth() : $fromDate;


$toDate = getParam('toDate');
$toDate = $toDate == '' ? lasDayMonth() : $toDate;



$sql = "SELECT r.REQUISITION_NO, r.REQUISITION_DATE, rt.REQUEST_NAME, c.challan_no,
CONCAT(e.FIRST_NAME,' ',e.LAST_NAME, '->', e.CARD_NO) AS employeeName, 
p.PRODUCT_NAME, SUM(ci.quantity) AS qty,
CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept

FROM requisition r
LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=r.OFFICE_TYPE_ID
LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=r.BRANCH_DEPT_ID
INNER JOIN challan c ON r.REQUISITION_ID IN (c.requisition_id)
LEFT JOIN challan_item ci ON ci.challanid=c.challanid
LEFT JOIN product p ON p.PRODUCT_ID=ci.productid
LEFT JOIN employee e ON e.EMPLOYEE_ID=r.CREATED_BY
LEFT JOIN request_type rt ON rt.REQUEST_TYPE_ID=r.REQUISITION_TYPE_ID
WHERE REQUISITION_TYPE_ID=1 AND c.date_time BETWEEN '$fromDate' AND '$toDate'
GROUP BY r.REQUISITION_ID, p.PRODUCT_ID";


?>


<div class="easyui-layout" style="width:1100px; margin: auto; height:550px;">  
    <div Title='Delivered Challan list' data-options="region:'center'"  style="background-color:white; "> 
        <form method="GET" action="" class="formValidate">
            <table>
                <tr>
                    <td>From Date: <input type="text" name="fromDate" value="<?php echo $fromDate; ?>" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                    <td>From Date: <input type="text" name="toDate" value="<?php echo $toDate; ?>" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                </tr>
            </table>
            <button type="submit" class="button">Search</button>
        </form>

        <table class="easyui-datagrid" id="example">
            <thead>
                <tr>
                    <th field="1">SL.</th>
                    <th field="2">Challan. No</th>
                    <th field="3">Requisition No</th>
                    <th field="4">Branch/Dept.Name</th>
                    <th field="5" align="left">Requisition From</th>
                    <th field="6" align="left">Product</th>
                    <th field="7" align="right">Qty</th>
                    <th field="8">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $db->query($sql);
                while ($rec = fetch_object($query)) {
                    $sl++;
                    ?> 
                    <tr>
                        <td><?php echo $sl; ?></td>
                        <td align="left"><strong><a href='challan_details.php?challanid=<?php echo $rec->challanid; ?>&requisition=<?php echo $rec->REQUISITION_NO; ?>' target='_blank'><?php echo $rec->challan_no; ?></a></strong></td>
                        <td align="left"><?php echo $rec->REQUISITION_NO; ?></td>
                        <td align="left"> <?php echo $rec->branch_dept; ?></td>
                        <td align="left"><?php echo $rec->employeeName; ?></td>
                        <td align="left"><?php echo $rec->PRODUCT_NAME; ?></td>
                        <td align="right"><?php echo $rec->qty; ?></td>
                        <td><?php //echo $rec->challan_status_name;     ?></td>
                    </tr>

                    <?php
                    $sub_total = $sub_total + $rec->total;
                }
                ?>
            </tbody>
        </table>

    </div>

</div>  
</div>
</div>


<?php include("../body/footer.php"); ?>