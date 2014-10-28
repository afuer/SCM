<?php
include '../lib/DbManager.php';
include "../body/header.php";

$itApprovalId = getParam('itApprovalId');
$ReqNo = getParam('ReqNo');
$FromDate = getParam('FromDate');
$FromDate = $FromDate == '' ? firstDayMonth() : "$FromDate";
$ToDate = getParam('ToDate');
$ToDate = $ToDate == '' ? lasDayMonth() : "$ToDate";



$res = '';

$res.=$itApprovalId != '' ? " AND si.IT_APPROVAL_ID='$itApprovalId'" : "";
$res.=$ReqNo != '' ? " AND so.REQUISITION_NO='$ReqNo'" : "";




$sql_produc_list = "SELECT si.PRODUCT_ID,
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
            LEFT JOIN it_store_product_approval spa ON spa.IT_APPROVAL_ID=si.IT_APPROVAL_ID
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
            AND spa.APPROVAL_DATE BETWEEN '$FromDate' AND '$ToDate'
            AND si.DETAILS_STATUS=15 AND si.STATUS_APP_LEVEL=3 $res
            ORDER BY so.REQUISITION_ID DESC";
//AND dh.delivery_qty IS NULL

$sql = query($sql_produc_list);
?>
<div class="panel-header">Store Item Details</div>  


<div style="background-color:white; padding: 10px 2px; "> 
    <fieldset>
        <legend>Search</legend>

        <form action="" method="GET">
            <table class="table">
                <tr>
                    <td width='100'>Requisition No:</td>
                    <td colspan="3"><input type="text" name="ReqNo" value="<?php echo $ReqNo; ?>" id="ReqNo" class="ReqNo"/></td>
                </tr>
                <tr>
                    <td>From Date:</td>
                    <td><input type="text" name="FromDate" value="<?php echo $FromDate; ?>" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                    <td width='100'>To Date:</td>
                    <td><input type="text" name="ToDate" value="<?php echo $ToDate; ?>" class="easyui-datebox" value="" data-options="formatter:myformatter,parser:myparser"/></td>
                </tr>
            </table>
            <button type="submit" class="button">Search</button>
        </form>
    </fieldset>

    <form name="frm" action="" method='POST' autocomplete="off" class="formValidate">
        <table class="ui-state-default">
            <thead>
            <th width="20">SL.</th>
            <!--<th width="50">Chk</th>-->
            <th width="100">Requisition No</th>
            <th>Req.Person</th>
            <th>Branch/Dept</th>
            <th width="200">Product Name</th>
            <th>Supplier Name</th>
            <th width="80">Ref. Date</th>
            <th>Bill No</th>
            <th>Cost Center(Sol)</th>
            <th width="50">Delivery Qty </th>
            <th width="50">Unit Price </th>
            <th width="50">Total Price </th>
            </thead>
            <tbody>

                <?php
                while ($rec = fetch_object($sql)) {
                    $totall++;
                    ?>

                    <tr class="datagrid-row">
                        <td><?php echo $totall; ?>.</td>
                        <!--<td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" /></td>-->
                        <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                            <input type='hidden' name='costCenter[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->COST_CENTER_ID; ?>' />
                            <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' />
                        </td> 
                        <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                        <td><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                        <td><?php echo $rec->PRODUCT_NAME; ?></td>
                        <td><?php echo $rec->SUPPLIER_NAME; ?></td>
                        <td><?php echo $rec->REF_DATE; ?></td>
                        <td><?php echo $rec->BILL_NO; ?></td>
                        <td><?php echo $rec->COST_CENTER_NAME . '(' . $rec->SOL_NAME . ')'; ?></td>
                        <td><?php echo $rec->APPROVE_QTY; ?></td>
                        <td><?php echo formatMoney($rec->UNIT_PRICE); ?></td>
                        <td><?php echo formatMoney($rec->APPROVE_QTY * $rec->UNIT_PRICE); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!--<input type="submit" class="button" value='Submit' name='approved' id="approved" />-->


    </form>
</div>  

<?php include '../body/footer.php'; ?>