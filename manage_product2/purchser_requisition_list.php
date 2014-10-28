<?php
include '../lib/DbManager.php';


$sendForCS = getParam('sendForCS');



include("../body/header.php");
?>

<div class="easyui-layout" style="width:1100px; margin: auto; height:1000px;">  
    <div data-options="region:'center'" Title='Requisition List' style="padding: 10px 10px; background-color:white; "> 


        <table class="easyui-datagrid">
            <thead>
                <tr>
                    <th field="name1" align="center">SL.</th>
                    <th field="name2"width='100'>Requisition No</th>
                    <th field="name7">Req Date</th>
                    <th field="name5">Branch/Dept</th>
                    <th field="name3">Requisition From</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //$ProcessDeptId
                $PurchaseSql = "SELECT pr.PRODUCT_CODE,
                                si.PRODUCT_ID, si.REQUISITION_ID, 
                                so.REQUISITION_NO,
                                max(so.PRIORITY_ID) as PRIORITY_ID,
                                pr.PRODUCT_NAME, DATE_FORMAT(so.REQUISITION_DATE,'%e %b %Y') AS REQUISITION_DATE,
                                sum(IFNULL(si.QTY,0)) as quantities,
                                SUM(IFNULL(si.CS_QTY,0)) AS CS_QTY,
                                CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
                                CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName'

                                from requisition_details si
                                left join requisition so on so.REQUISITION_ID=si.REQUISITION_ID
                                left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                                LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                                LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
                                LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY                                
                                LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
                                WHERE  so.CANCELLED=0 AND so.REQUISITION_TYPE_ID=2 AND so.REQUISITION_STATUS_ID=3
                                AND so.USER_LEVEL_ID ='$UserLevelId' AND so.PROCESS_DEPT_ID='$ProcessDeptId'
                                GROUP BY so.REQUISITION_ID HAVING quantities-CS_QTY>0
                                ORDER BY si.REQUISITION_ID DESC";

                $PurchaseResult = query($PurchaseSql);
                //PRODUCT_TYPE_ID=1 and so.PROCESS_DEPT_ID='1' AND and si.status_app_level=0

                $count = 0;
                while ($rec = fetch_object($PurchaseResult)) {
                    $count++;
                    ?> 
                    <tr>
                        <td><?php echo $count; ?>.</td>
                        <td align="left"><a href="approve_pr_product.php?req_no=<?php echo $rec->REQUISITION_NO; ?>&condition=<?php echo "product_type=0 and"; ?>" target="_blank"><?php echo $rec->REQUISITION_NO; ?></a></td>
                        <td align="left"><?php echo $rec->REQUISITION_DATE; ?></td>
                        <td align="center"><?php echo $rec->branch_dept; ?></td>
                        <td align="left"><?php echo $rec->employeeName; ?></td>
                        <?php
                    }
                    ?>
            </tbody>
        </table>

    </div>
</div>




<?php
include("../body/footer.php");
?>