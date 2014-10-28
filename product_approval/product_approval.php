<?php
include_once '../lib/DbManager.php';


/* workflow manager  */

if (isset($_POST['approveSubmit'])) {


    $ObjWorkflow = new WorkFlowManager();
    $sl = $ObjWorkflow->MaxSl($SearchID);

    $approveStatus = '1';
    $approvalComment = getParam('approvalComment');
    $workflowManagerID = NextId('workflow_manager', 'WORKFLOW_MANAGER_ID');
    $Module = 'lafarge_procurement';
    $createdDate = date('Y-m-d');
    /*
     * Inserts into workflow. No calculation is needed here. Only the history of approval persons are preserved here.
     */
    $InsertWorkflow = "INSERT INTO workflow_manager (WORKFLOW_MANAGER_ID, REQUISITION_ID, MODULE_NAME, EMPLOYEE_DESIGNATION_ID, SL, APPROVE_STATUS, APPROVAL_COMMENT, EMPLOYEE_ID, CREATED_DATE) 
        VALUES ('$workflowManagerID', '$SearchID', '$Module', '$Designation', '$sl', '$approveStatus', '$approvalComment', '$user_name', '$createdDate')";
    query($InsertWorkflow);



    /*
     * Approval Limit of logged In user and current route. If there is no approval limit for the current user 
     * (i.e. Procurement Officer) it will define the approval limit to 0 as procurement officer also approves for the first time. 
     */
    $ApprovalLimit = findValue("SELECT APPROVAL_LIMIT 
                    FROM delegation_authority 
                    WHERE PROCESS_DEPT_ID='7' AND DESIGNATION_ID='1' 
                    AND BRANCH_DEPT_ID='2'");

    $ApprovalLimit = $ApprovalLimit == '' ? 0 : $ApprovalLimit;


    /*
     * Identifying the next minimum approval amount and its designation and location to update the master table (proc_procurement)
     * We finally want the next employee ID to be updated in master table 
     * 
     */


    $SQLminLimitedEmployee = find("SELECT da.DESIGNATION_ID, da.PROCESS_DEPT_ID, 
    MIN(APPROVAL_LIMIT) AS minApprovalLimit, USER_LEVEL_ID

    FROM delegation_authority da
    WHERE PROCESS_DEPT_ID='7' AND APPROVAL_LIMIT>'$ApprovalLimit'");



//It is the total amount of selected suppliers i.e. the OQ Amount which is to be approved.
    $totOQAmount = findValue("SELECT SUM(IFNULL(cs_qty,0)*IFNULL(rate,0)) AS 'price'
                    FROM price_comparison_pro_req_qty WHERE price_comparison_id='20'");

// i.e if delegeted amount is less than the total OQ Amount then update master table by next approval persons ID
    if ($ApprovalLimit < $totOQAmount) {


        $updateMaster = "UPDATE proc_procurement SET USER_LEVEL_ID='$SQLminLimitedEmployee->USER_LEVEL_ID' WHERE comparisonid='$SearchID'";
        query($updateMaster);
    } else { // for the last person
        $updateMaster = "UPDATE proc_procurement SET USER_LEVEL_ID=null, approved='1' WHERE comparisonid='$SearchID'";
        query($updateMaster);
    }
    echo "<script>location.replace('evaluations.php');</script>";
}


/* End workflow  */
?>
        <form method="POST">
            <table width="997" height="496" border="1" cellpadding="0" cellspacing="0" class="ui-state-default">
                <tr>
                    <td width="993" colspan="2">
                        <table width="844" border="1" cellpadding="0" cellspacing="0" class="ui-state-default">
                            <thead>
                            <th width="27" bgcolor="#0000FF"><div align="center" class="style1">SL</div></th>
                            <th width="344" bgcolor="#0000FF"><div align="center" class="style1">Supplier </div></th>
                            <th width="74" bgcolor="#0000FF"><div align="center" class="style1">Quantity &amp; Unit </div></th>
                            <th width="68" bgcolor="#0000FF"><div align="center" class="style1">Quoted Unit Price </div></th>
                            <th width="96" bgcolor="#0000FF"><div align="center" class="style1">Quoted Total Price </div></th>
                            <th width="195" bgcolor="#0000FF"><div align="center" class="style1">Selection Comments </div></th>
                            <th width="20" bgcolor="#0000FF"><div align="center" class="style1">Selection </div></th>
                            </thead>

                            <?php
                            $supplierListSQL = "SELECT PROC_PROCUREMENT_DETAIL_ID, SELCOMMENT, supplier_code, supplier_name, sum(release_quantity * responded_amount)AS amount, ISSELECTED
                            FROM proc_procurement_detail
                            WHERE order_number = '$oqNumber'
                            GROUP BY supplier_name";
                            $resultSupp = query($supplierListSQL);
                            while ($resultObj = fetch_object($resultSupp)) {
                                ?>
                                <tr>
                                    <td><?php echo++$SL1; ?></td>
                                    <td><?php echo $resultObj->supplier_name; ?></td>
                                    <td align="right"><?php echo "---"; ?></td>
                                    <td align="right"><?php echo "---"; ?></td>
                                    <td align="right"><?php echo formatMoney($resultObj->amount); ?></td>
                                    <td><input type="text" name="selCom[<?php echo $resultObj->PROC_PROCUREMENT_DETAIL_ID; ?>]" value="<?php echo $resultObj->SELCOMMENT; ?>"></td>
                                    <td align="center"><input type="checkbox" name="selectedSupplier[<?php echo $resultObj->PROC_PROCUREMENT_DETAIL_ID; ?>]" <?php
                                        if ($resultObj->ISSELECTED == '1') {
                                            echo "checked";
                                        } else {
                                            echo '';
                                        }
                                        ?>></td>

                                </tr>
                            <?php } ?>
                            <tr><td colspan="7" align="right"><button name="update" class="button">Update</button></td></tr>

                        </table>
                        <br /> 
                        <?php
                        $SelectedSupplierSQL1 = "SELECT PROC_PROCUREMENT_DETAIL_ID, supplier_code, supplier_name, sum(release_quantity * responded_amount)AS amount
                        FROM proc_procurement_detail
                        WHERE order_number = '$oqNumber' AND ISSELECTED='1'
                        GROUP BY supplier_name";
                        $selSuppName1 = query($SelectedSupplierSQL1);
                        $selSuppName2 = query($SelectedSupplierSQL1);
                        ?>
                        <table width="845" border="1" cellpadding="0" cellspacing="0">
                            <tr><td colspan="6" ><strong>Final Selection</strong></td></tr>
                            <tr>
                                <td width="45"><strong>SL</strong></td>
                                <?php while ($obj1 = fetch_object($selSuppName1)) { ?>
                                    <td><div align="center"><strong><?php echo++$SL; ?></strong></div></td>
                                <?php } ?>
                                <td width="100"><div align="center"><strong>Approved Amount </strong></div></td>
                                <td width="200"><div align="center"><strong>Comments</strong></div></td>
                            </tr>
                            <tr>
                                <td><strong>Supplier</strong></td>
                                <?php while ($obj2 = fetch_object($selSuppName2)) { ?>
                                    <td><?php echo $obj2->supplier_name; ?></td>
                                <?php } ?>
                                <td align="right"></td>
                                <td></td>
                            </tr>
                        </table>
                        <br />
                    </td>
                </tr>
                <tr>
                    <td class="rem"><b>Remarks:</b></td>
                    <td><textarea name="approvalComment" cols="55" rows="3"></textarea></td>
                </tr>
                <tr>
                    <td class="rem">
                        <input type="submit" id="approveSubmit" name="approveSubmit" value="APPROVE AND SEND" class="button" /> |
                        <input type="submit" name="rejectSubmit" value="REJECT" class="button"/></td>
                </tr>
            </table>
        </form>
    </div>