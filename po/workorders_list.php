<?php
include '../lib/DbManager.php';
include "../body/header.php";




$po_status = getParam('po_status');


$start_date = getParam('start_date');
$end_date = getParam('end_date');
$start_date = $start_date == '' ? FirstDayLastThreeMonth() : $start_date;
$end_date = $end_date == '' ? lasDayMonth() : $end_date;


$searchkey = getParam('searchkey');
$searchkey2 = getParam('searchkey2');

$search = getParam('search');
if (empty($search) && ($level_designation == 1)) {
    $personal_po = " and pro.createdby='$employeeid'";
} else if (empty($search) && ($level_designation == 2)) {
    $personal_po = "and pro.status=1";
}

if ($searchkey != "") {
    $condition = " and pro.po_no like '%" . $searchkey . "%' ";
}

if ($po_status != '') {
    $condition .= " and pro.`status`='$po_status'";
}

if ($searchkey2 != "") {
    $employeeid = findValue("SELECT  employeeid FROM employee where cardno='$searchkey2'");

    $condition2 = " and createdby='$employeeid'";
}

$sql = "select pro.purchase_order_id,
            pro.order_no,
            pro.comparison_id, 
            com.comparative_code, 
            sp.SUPPLIER_NAME,
            pro.order_date, 
            pro.cancelled, 
            pro.orderids, 
            emp.FIRST_NAME, emp.LAST_NAME,
            pro.branch_dept_id,
            delivery_date,
            ps.purchase_status_name,
            pro.discount,  
            pro.vat, CARD_NO,
            sum(pr_it.qty*pr_it.unit_price-((pr_it.discount/100)*(pr_it.qty*pr_it.unit_price))) as net_value
            
            from purchase_order pro
            left join purchase_order_details pr_it on pro.purchase_order_id = pr_it.purchase_order_id
            left join product p on p.PRODUCT_ID = pr_it.product_id
            left join supplier sp on pro.supplier_id = sp.SUPPLIER_ID
            left join employee emp on pro.created_by = emp.EMPLOYEE_ID
            left join master_user u on u.EMPLOYEE_ID = emp.EMPLOYEE_ID
            left join price_comparison com on com.comparisonid = pro.comparison_id
            LEFT JOIN purchase_status AS ps ON ps.purchase_status_id=pro.purchase_order_status
            WHERE pro.order_date between '$start_date' and '$end_date' 
            group by pro.purchase_order_id order by pro.purchase_order_id desc";

$query_com = query($sql);

$po_list = rs2array(query("SELECT purchase_status_id, purchase_status_name FROM purchase_status"));


?>

<div class="easyui-layout" style="height:700px; margin: auto;">  
    <div title="Purchase Order  List" data-options="region:'center'" class="easyui-panel" >  
        <form action="" method="GET">
            <table width="100%" class="table">
                <tr>
                    <td width="100">PO/WO No.</td>
                    <td><input type="text" name="searchkey" value="<?php echo $searchkey; ?>" ></td>
                    <td width="100">Employee ID</td>                                                    
                    <td><input type="text" name="searchkey2" value="<?php echo $searchkey2; ?>" ></td>
                </tr>
                <tr>
                    <td>From:</td>
                    <td colspan="3">
                        <input type="text" name="start_date" value="<?php echo $start_date; ?>"  class="easyui-datebox"/>
                        to <input type="text" name="end_date" value="<?php echo $end_date; ?>" class="easyui-datebox"/>
                    </td>
                </tr>
                <tr>
                    <td>PO Status:</td>
                    <td><?php comboBox('po_status', $po_list, $po_status, TRUE); ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <button type="submit" name="search" class="button">Search</button>
        </form>
        <hr/>

        <table class="easyui-datagrid">
            <thead >
                <tr>
                    <th field='1'  width='30'>SL.</th>
                    <th field='2' ><?php etr("Date") ?></th>
                    <th field='3'  align="center"><?php etr("CS No") ?></th>
                    <th field='4'  align="center"><?php etr("PO ID") ?></th>
                    <th field='5' ><?php etr("Supplier") ?></th>
                    <th field='6' >Delivery Date</th>
                    <th field='7' ><?php etr("Created By") ?></th>
                    <th field='8'  align="right">WO Value </th>
                    <th field='9'  align="center"><?php etr("Status") ?></th>
                </tr>
            </thead>
            <?php
            $sum_wo_value = "";
            while ($rec_com = fetch_object($query_com)) {
                $sl++;

                $net_value = $rec_com->net_value;
                $total_discount = ($rec_com->discount / 100) * $net_value;
                $discount_less_amount = $net_value - $total_discount;
                $total_vat = ($rec_com->vat / 100) * $discount_less_amount;
                $sub_total = ($net_value + $total_vat) - $total_discount;
                ?>
                <tr>
                    <td class="sn"><?php echo $sl; ?></td>
                    <td><?php echo bddate($rec_com->order_date); ?></td>
                    <td align="center"><a href="../manage_product/evaluation_statement.php?comparison_id=<?php echo $rec_com->comparison_id; ?>" target="_blank"><?php echo OrderNo($rec_com->comparison_id); ?></a></td>
                    <td align="center"><a href="po_view.php?poid=<?php echo $rec_com->purchase_order_id; ?>" target="_blank"><?php echo $rec_com->order_no; ?></a></td>
                    <td><?php echo $rec_com->SUPPLIER_NAME; ?></td>
                    <td><div align="center"><?php echo bddate($rec_com->delivery_date); ?></div></td>
                    <td><?php echo $rec_com->FIRST_NAME . ' ' . $rec_com->LAST_NAME . ' (' . $rec_com->CARD_NO . ')'; ?></td>
                    <td align="right"><?php echo number_format($sub_total, 2, '.', ','); ?></td>
                    <td align="center"><?php echo $rec_com->purchase_status_name; ?></td>
                </tr>  
                <?php
                $sum_wo_value += $sub_total;
            }
            ?>
            <tr>
                <td >&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="center"><strong>Total</strong></td>
                <td align="right"><strong><?php echo formatMoney($sum_wo_value); ?></strong></td>
                <td>&nbsp;</td>
            </tr> 
        </table>

    </div>
</div>


<?php include("../body/footer.php"); ?>