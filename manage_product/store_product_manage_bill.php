<?php
include_once '../lib/DbManager.php';

//include "../body/header.php";




if (isSave()) {

    if ($finalize == 'finalize') {
        $orderids = getParam("orderids");
        $pending_pre = getParam("pending_pre");
        $delivery_qty = getParam("delivery_qty");
        $orderid = getParam('orderid');


        foreach ($orderids as $key => $value) {

            $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

            $sql_details = "update requisition_details set
            APPROVE_QTY=$deliver_qty,
            DETAILS_STATUS=4, 
            STATUS_APP_LEVEL=1 
            WHERE REQUISITION_DETAILS_ID='$key'";
            $db->sql($sql_details);
            $requisition_id = $orderid[$key];
        }
    } else {

        $orderids = getParam("orderids");
        $pending_pre = getParam("pending_pre");
        $delivery_qty = getParam("delivery_qty");
        $orderid = getParam('orderid');


        foreach ($orderids as $key => $value) {

            $deliver_qty = $delivery_qty[$key] == '' ? 0 : $delivery_qty[$key];

            $sql_details = "update requisition_details set
            APPROVE_QTY=$deliver_qty,
            DETAILS_STATUS=5, 
            STATUS_APP_LEVEL=1 
            WHERE REQUISITION_DETAILS_ID='$key'";
            $db->sql($sql_details);
            $requisition_id = $orderid[$key];
        }
    }
    
    //echo "<script>location.replace('store_item_requisition_list.php');</script>";
}

$res = $UserLevelId == '6' ? " si.DETAILS_STATUS=3 " : " si.DETAILS_STATUS=4 ";

echo $sql_produc_list = "SELECT si.PRODUCT_ID,
        si.REQUISITION_ID, si.REQUISITION_DETAILS_ID,
        pr.PRODUCT_NAME, 
        si.QTY as quantities,
        so.CREATED_BY,
        dv.DIVISION_NAME, 
        so.OFFICE_TYPE_ID, 
        so.BRANCH_DEPT_ID,
        so.REQUISITION_NO,
        e.FIRST_NAME, e.LAST_NAME,
        e.CARD_NO, ot.OFFICE_NAME, bd.BRANCH_DEPT_NAME,
        APPROVE_QTY,
        si.DETAILS_STATUS

        FROM requisition_details si
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
        left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
        left join division dv on dv.DIVISION_ID=so.DIVISION_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY
        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID

        WHERE so.REQUISITION_STATUS_ID=3 AND so.PROCESS_DEPT_ID IN ($ProcessDeptId)
        AND si.DETAILS_STATUS=3 ORDER BY si.REQUISITION_ID DESC";
//AND dh.delivery_qty IS NULL

$sql = query($sql_produc_list);
?>
<form name="frm" action="" method='POST' autocomplete="off" class="formValidate">


    <h2>Requisition Pending Item</h2>
    <table class="ui-state-default">
        <thead>
        <th width="20">SL.</th>
        <th width="50">Chk</th>
        <th width="100">Requisition No</th>
        <th>Req.Person</th>
        <th>Branch/Dept</th>
        <th width="200">Product Name</th>
        <th width="50">Delivery Qty </th>
        </thead>
        <tbody>

            <?php
            while ($rec = fetch_object($sql)) {
                $totall++;

                $pending_pre = $rec->quantities - $rec->deliverd;
                $available = $rec->stock - $rec->allocated;
                ?>

                <tr class="datagrid-row">
                    <td><?php echo $totall; ?>.</td>
                    <td align="center"><input type="checkbox" name="orderids[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" /></td>
                    <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                        <input type='hidden' name='orderid[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' /></td> 
                    <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME . ' (' . $rec->CARD_NO . ')'; ?></td>
                    <td><?php echo $rec->OFFICE_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?></td>
                    <td><?php echo $rec->PRODUCT_NAME; ?></td>
                    <td align="center"><input type="text" name="delivery_qty[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" size="10" value="<?php echo $rec->APPROVE_QTY; ?>" min="0" /></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <?php
    if ($UserLevelId == '6') {
        echo "<button type='submit' class='button' value='submit' name='save'>Submit</button>";
    } else {
        echo "<button type='submit' class='button' value='finalize' name='save'>Finalize</button>";
    }
    ?>
</form>



<?php
//include '../body/footer.php'; ?>