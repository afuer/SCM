<?php
include_once '../lib/DbManager.php';
include '../body/header.php';

$searchId = getParam('comparison_id');


$rec_com = find("select * from price_comparison where comparisonid='$searchId'");

if (isSave()) {
    $footer = getParam('footer');
    $body = getParam('body');
    $subject = getParam('subject');
    $cc = getParam('cc');
    $date = getParam('date');
    $ref = getParam('ref');


    $approvalComment = getParam('comments');
    $Module = 'Product Approval';
    /*
     * Inserts into workflow. No calculation is needed here. Only the history of approval persons are preserved here.
     */
    $insert_sql = "INSERT INTO requisition_approval (CS_ID, REF, DATE, CC, `SUBJECT`, BODY, FOOTER, `STATUS`, CREATED_BY, CREATED_DATE) 
                    VALUES('$searchId', '$ref', '$date', '$cc', '$subject', '$body', '$footer',  '1', '$employeeId', NOW())";

    query($insert_sql);



    /*
     * Approval Limit of logged In user and current route. If there is no approval limit for the current user 
     * (i.e. Procurement Officer) it will define the approval limit to 0 as procurement officer also approves for the first time. 
     */
    $sql_limit = "SELECT APPROVAL_LIMIT 
                    FROM delegation_authority 
                    WHERE PROCESS_DEPT_ID='$ProcessDeptId' AND DESIGNATION_ID='$Designation'";
    $ApprovalLimit = findValue($sql_limit);

    $ApprovalLimit = $ApprovalLimit == '' ? 0 : $ApprovalLimit;


    /*
     * Identifying the next minimum approval amount and its designation and location to update the master table (proc_procurement)
     * We finally want the next employee ID to be updated in master table 
     * 
     */

    $sql_limit = "SELECT da.DESIGNATION_ID, da.PROCESS_DEPT_ID, 
    MIN(APPROVAL_LIMIT) AS minApprovalLimit, mu.USER_LEVEL_ID

    FROM delegation_authority da

    INNER JOIN employee e ON e.DESIGNATION_ID=da.DESIGNATION_ID
    INNER JOIN master_user mu ON mu.ROUTE_ID=da.PROCESS_DEPT_ID AND mu.EMPLOYEE_ID=e.EMPLOYEE_ID
    WHERE PROCESS_DEPT_ID='$ProcessDeptId' AND APPROVAL_LIMIT>'$ApprovalLimit'";


    $SQLminLimitedEmployee = find($sql_limit);


    //It is the total amount of selected suppliers i.e. the OQ Amount which is to be approved.
    $totOQAmount = findValue("SELECT SUM(IFNULL(cs_qty,0)*IFNULL(unite_price,0)) AS 'price'
    FROM price_comparison_pro_req_qty pcq
    INNER JOIN price_comparison_details pcd ON pcd.comparison_id=pcq.price_comparison_id AND pcd.productid=pcq.product_id
    WHERE price_comparison_id='$searchId' AND selected=1");

    // i.e if delegeted amount is less than the total OQ Amount then update master table by next approval persons ID
    if ($ApprovalLimit < $totOQAmount) {

        $updateMaster = "UPDATE requisition_approval SET PRESENT_LOCATION_ID=NULL, USER_LEVEL_ID='$SQLminLimitedEmployee->USER_LEVEL_ID' WHERE APPROVAL_NOTE_ID='$searchId'";
        query($updateMaster);
    } else { // for the last person
        $updateMaster = "UPDATE requisition_approval SET USER_LEVEL_ID=null, APPROVED='3' WHERE APPROVAL_NOTE_ID='$searchId'";
        query($updateMaster);
    }
    echo "<script>location.replace('index.php');</script>";
}
?>

<div class="easyui-layout" style="width:950px; margin: auto; height:1200px;">  
    <div data-options="region:'center'" Title='Product List' style="padding: 10px 10px; background-color:white; "> 
        <form action="" method="POST">
            <input type="hidden" name="comparison_id" value="<?php echo $searchId; ?>"/>


            <div>
                <div class="float-left fc">Ref: <input type="text" name='ref'/></div>
                <div class="float-right fc">Date: <input type="text" name="date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/></div>
            </div>
            <div class="fc"><textarea name="cc"></textarea></div>



            <textarea class="fc" name="subject" style="width:100%; height:50px;">Subject: Limit of Cash </textarea>
            <textarea name="body" style="width:100%; height:100px;">Dear Sir, </textarea>
            <br>
            <br>

            <table class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field='1'>SL.</th>
                        <th field='2' width='250'>Product Name</th>
                        <th field='3' width='250'>Selected Supplier</th>
                        <th field='4'>Qty</th>
                        <th field='5'>Rate</th>
                        <th field='6'>Total</th>
                    </tr>
                </thead>

                <?php
                $supplierListSQL = "SELECT  pc.detailsid, SUPPLIER_NAME, pc.supplier_id, pcq.cs_qty, 
                pcq.rate, pcq.product_id, sl, p.PRODUCT_NAME, unite_price
                FROM price_comparison_details pc
                LEFT JOIN product p ON p.PRODUCT_ID=pc.productid
                LEFT JOIN supplier sp ON sp.SUPPLIER_ID = pc.supplier_id
                LEFT JOIN price_comparison_pro_req_qty pcq ON pcq.price_comparison_id=pc.comparison_id AND pcq.product_id=pc.productid
                WHERE pc.comparison_id='$searchId' AND selected=1";
                $resultSupp = query($supplierListSQL);
                while ($resultObj = fetch_object($resultSupp)) {
                    //$unit_price = findValue("SELECT unite_price FROM price_comparison_details WHERE comparison_id='$searchId' AND productid='$row->productid' AND sl='$resultObj->sl'");

                    $grand_total+=$resultObj->unite_price;
                    ?>
                    <tr>
                        <td><?php echo++$SL1; ?></td>
                        <td><?php echo $resultObj->PRODUCT_NAME; ?></td>
                        <td><?php echo $resultObj->SUPPLIER_NAME; ?></td>
                        <td align="right"><?php echo $resultObj->cs_qty; ?></td>
                        <td align="right"><?php echo formatMoney($resultObj->unite_price); ?></td>
                        <td><?php echo formatMoney($resultObj->cs_qty * $resultObj->unite_price); ?></td>

                    </tr>
                <?php } ?>
                <tr style="background-color:gray;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Grand Total</td>
                    <td><?php echo formatMoney($grand_total); ?></td>                
                </tr>
            </table>
            <br/>

            <textarea name="footer" style="width:100%; height:50px;">Your Replay............. </textarea>




            <h2>Approval History</h2>
            <table  class="easyui-datagrid">
                <thead>
                    <tr>
                        <th field='1' width="20"><b>SL.</b></th>
                        <th field='2' width="100"><b>Date</b></th>
                        <th field='3'>Employee Name</th>
                        <th field='4' width="150"><b>Designation</b></th>
                        <th field='5'><b>Comments</b></th>
                    </tr>
                </thead>
                <?php
                $query_his = query("SELECT wm.WORKFLOW_MANAGER_ID, emp.FIRST_NAME, emp.CARD_NO, 
        emp.LAST_NAME, dis.DESIGNATION_NAME, wm.CREATED_DATE, APPROVAL_COMMENT 
                            FROM workflow_manager wm 
                            LEFT JOIN employee emp ON wm.LINE_MANAGER_ID = emp.EMPLOYEE_ID
                            LEFT JOIN designation dis ON dis.DESIGNATION_ID=emp.DESIGNATION_ID
                            WHERE wm.REQUISITION_ID='$searchId' AND wm.MODULE_NAME='CS'");

                $num_rows = mysql_num_rows($query_his);
                if ($num_rows > 0) {
                    while ($rec_h = fetch_object($query_his)) {
                        $sn++;
                        ?>
                        <tr>
                            <td><?php echo $sn . "."; ?></td>
                            <td><?php echo bddate($rec_h->CREATED_DATE); ?></td>
                            <td><?php echo $rec_h->FIRST_NAME . ' ' . $rec_h->LAST_NAME . '(' . $rec_h->CARD_NO . ')'; ?></td>
                            <td><?php echo $rec_h->DESIGNATION_NAME; ?></td>
                            <td><?php echo $rec_h->APPROVAL_COMMENT; ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No Record Foud</td></tr>";
                }
                ?>
            </table>
            <br/>
            <?php
            if ($rec_com->status < 3) {
                ?>


                <table width="100%">
                    <tr>
                        <td valign="top" width="100">Comments: </td>
                        <td><textarea name="comments" rows="4" cols="48"></textarea></td>
                    </tr>
                </table>
                <input type="submit" name="save" value="Apoprove & Send"/> 
                <a href="../manage_product/evaluation_statement.php?comparison_id=<?php echo $searchId; ?>" target="_blank">View CS</a>
            </form>
            <?php
        }
        ?>
        </form>
    </div>
</div>

<?php
include '../body/footer.php';
?>