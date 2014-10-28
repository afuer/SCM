<?php
include '../lib/DbManager.php';

$db = new DbManager();
$db->OpenDb();


$val = getParam('val');
$action = getParam('action');
$branch_dept_type = getParam('branch_dept_type');
$field_name = ($val == 1) ? "DEPARTMENT_NAME" : "BRANCH_NAME";
$field_id = ($val == 1) ? "department_id" : "branch_id";
$table_name = ($val == 1) ? "department" : "branch";

$sql = "select dp_br.$field_name, dp_br.$field_id 
        from (
        select PRODUCT_ID, REQUISITION_ID, sum(si.QTY) as quantity 
        from requisition_details si group by si.PRODUCT_ID, si.REQUISITION_ID
        ) si
        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID 
        left join (
        select req_id, product_id, challan_id, sum(delivery_qty) as deliverd 
        from app_product_delivery_history group by id, req_id, product_id
        ) dh on si.REQUISITION_ID=dh.req_id and si.PRODUCT_ID=dh.product_id 

        left join requisition so on so.REQUISITION_ID = si.REQUISITION_ID 
        inner join $table_name dp_br on dp_br.$field_id = so.branch_dept_id
        left join challan ch on ch.challanid = dh.challan_id
        where so.OFFICE_TYPE_ID = '$val' $action and (pr.requisition_for=0 and pr.PROCESS_DEPT_ID!=1)  
        group by so.branch_dept_id ORDER BY dp_br.$field_name";

$sql_query = query($sql);
?>
<td>
    <select name="branch_dept_type">
        <option></option>
        <?php
        while ($rec = fetch($sql_query)) {
            ?>
            <option value="<?php echo $rec->$field_id; ?>" <?php
            if ($rec->$field_id == $branch_dept_type) {
                echo "selected";
            }
            ?>><?php echo $rec->$field_name; ?></option>
                <?php } ?>
    </select>
</td>
