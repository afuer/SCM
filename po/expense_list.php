<?php
include '../lib/DbManager.php';
include("../body/header.php");


$start_date = getParam('start_date');
$start_date = $start_date == '' ? FirstDayLastThreeMonth() : $start_date;
$end_date = getParam('end_date');
$end_date = $end_date == '' ? lasDayMonth() : $end_date;
$search = getParam('search');

$po = getParam('po');
$req_from = getParam('req_from');
$wo_value = getParam('wo_value');
$vendor_id = getParam('vendor_id');
$requisition_status_id = getParam('requisition_status_id');


$supplierList = $db->rs2array("SELECT SUPPLIER_ID, SUPPLIER_NAME FROM supplier ORDER BY SUPPLIER_NAME");
$requisitionStatusList = $db->rs2array("SELECT requisition_status_id, status_name FROM requisition_status ORDER BY status_name");

if (empty($search) && ($level_designation == 1)) {
    $personal_po = " and po.created_by='$employeeid'";
} else if (empty($search) && ($level_designation == 2)) {
    $personal_po = "and po.status=1";
}



$res = '';
$res.=$po == '' ? '' : " AND po.order_no='$po'";
$res.=$vendor_id == '' ? '' : " AND sp.SUPPLIER_NAME='$vendor_id'";
$res.=$req_from == '' ? '' : " AND (emp.FIRST_NAME LIKE '%$req_from%' OR emp.LAST_NAME LIKE '%$req_from%') ";
$res.=$requisition_status_id == '' ? '' : " AND po.purchase_order_status='$requisition_status_id'";


//echo "<br/><br/><br/><br/>";
$sql = "SELECT po.purchase_order_id,
        po.order_no,
        po.comparison_id,
        po.order_date,
        po.supp_ref,
        po.supplier_id,
        poi.product_id,
        po.purchase_order_status,
        sup.SUPPLIER_NAME,
        emp.FIRST_NAME, emp.LAST_NAME,
        po.discount, 
        po.vat,
        sum(poi.qty*poi.unit_price-((poi.discount/100)*(poi.qty*poi.unit_price))) as net_value,
        si.status_app_level							

        from purchase_order_details poi 
        left join purchase_order po on poi.purchase_order_id = po.purchase_order_id
        left join supplier sp on po.supplier_id = sp.SUPPLIER_ID
        left join employee emp on po.created_by = emp.EMPLOYEE_ID
        left join product p on p.PRODUCT_ID = poi.product_id
        left join requisition_details si on poi.product_id = si.PRODUCT_ID
        left join supplier sup on po.supplier_id = sup.SUPPLIER_ID
        WHERE po.order_date BETWEEN '$start_date' and '$end_date' AND po.purchase_order_status=4
        $res  GROUP BY po.purchase_order_id ORDER By po.purchase_order_id DESC";
// p.PROCESS_DEPT_ID = '$requisition_routeid' and 
$sql_result = query($sql);
?>                  

<div class="easyui-layout" style="height:700px; margin: auto;">  
    <div title="Expense Claim List" data-options="region:'center'" class="easyui-panel" style="padding: 5px 10px;" >  


        <form action="" method="GET" class="formValidate">
            <fieldset style="background: white;">
                <legend>Search</legend>

                <table class="table">
                    <tr>
                        <td width="100">PO: </td>
                        <td width="100"><input type="text" name="po" value="<?php echo $po; ?>"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="100">Requisition From: </td>
                        <td width="100"><input type="text" name="req_from" value="<?php echo $req_from; ?>"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="100">WO Value: </td>
                        <td width="100"><input type="text" name="wo_value" value="<?php echo $wo_value; ?>"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>From Date: </td>
                        <td><input type="text" name="start_date" value="<?php echo $start_date; ?>" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>To: </td>
                        <td><input type="text" name="end_date" value="<?php echo $end_date; ?>" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Vendor :</td>
                        <td><?php comboBox('vendor_id', $supplierList, $vendor_id, TRUE); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Status :</td>
                        <td><?php comboBox('requisition_status_id', $requisitionStatusList, $requisition_status_id, TRUE); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                </table>
                <button type="submit" name="search" class="button" >Search</button>
            </fieldset>
        </form>

        <table width="100%" class="ui-state-default">
            <thead>
            <th width='30'>SL.</th>
            <th width='80'>PO No </th>
            <th>Vendor</th>
            <th width="150" >WO/PO/SO Ref</th>
            <th width='150'><?php etr("Created By") ?></th>
            <th width="13%" ><div align="right">WO Value</div></th>         
            <th width="100" align="center">Paid up to date</th>         
            <th align="center">Status</th>
            <th width="150" align="center">Action</th>
            </thead>
            <?php
            $remaining = "";


            $sub_total = "";
            $num = 0;
            while ($rec = fetch_object($sql_result)) {
                $sl++;

                //---some problem faceing on main sql. this is a temporary solution
                $row = find("SELECT po.discount, po.vat,
			sum(poi.qty*poi.unit_price-((poi.discount/100)*(poi.qty*poi.unit_price))) as net_value
                    from purchase_order_details poi
                    left join purchase_order po on po.purchase_order_id=poi.purchase_order_id
		    WHERE po.purchase_order_id='$rec->purchase_order_id' 
                    GROUP BY po.purchase_order_id");

                $total_value = findValue("SELECT SUM(NET_PAY) as total 
                FROM fin_payment_approval_note
                WHERE PURCHASE_ORDER_ID='$rec->purchase_order_id' 
                GROUP BY PURCHASE_ORDER_ID");

                $net_value = $row->net_value;
                $total_discount = ($row->discount / 100) * $net_value;
                $discount_less_amount = $net_value - $total_discount;
                $total_vat = ($row->vat / 100) * $discount_less_amount;
                $sub_total = ($net_value + $total_vat) - $total_discount;
                ?> 
                <tr>
                    <td><?php echo $sl . "."; ?></td>
                    <td><a href="po_view.php?poid=<?php echo $rec->purchase_order_id; ?>" target="_blank"><?php echo $rec->order_no; ?></a></td>
                    <td><?php echo $rec->SUPPLIER_NAME; ?></td>
                    <td align="center"><?php echo $rec->supp_ref; ?></td>
                    <td><?php echo $rec->FIRST_NAME . ' ' . $rec->LAST_NAME; ?></td>
                    <td align="right"><?php echo formatMoney($sub_total); ?></td>
                    <td align="center">
                        <?php
                        //$po_no = $rec->order_no;
                        $paid_amount = findValue("SELECT sum(NET_PAY) as amount FROM fin_payment_approval_note WHERE PURCHASE_ORDER_ID='$rec->purchase_order_id' GROUP BY PURCHASE_ORDER_ID");
                        echo formatMoney($paid_amount);

                        $remaining = formatMoney($sub_total) - formatMoney($paid_amount);
                        //echo $sub_total . '==' . $paid_amount;
                        ?>
                    </td>
                    <td align="center"><?php echo po_status($rec->purchase_order_status); ?></td>
                    <td align="center">
                        <input type="hidden" name="po_no" value="<?php echo $rec->poid; ?>" />
                        <input type="hidden" name="referance" value="<?php echo $rec->supp_ref; ?>" />
                        <input type="hidden" name="name" value="<?php echo $rec->name; ?>" />
                        <input type="hidden" name="po_date" value="<?php echo $rec->orderdate; ?>" />
                        <input type="hidden" name="supplierid" value="<?php echo $rec->supplierid; ?>" />
                        <input type="hidden" name="amount" value="<?php echo $rec->total; ?>" />
                        <input type="hidden" name="productid" value="<?php echo $rec->productid; ?>" />
                        <input type="hidden" name="credit_account" value="<?php echo $rec->credit_account; ?>" />
                        <?php
                        //echo $remaining;
                        if (0 <= $remaining) {
                            ?>
                            <a href="expense_form.php?po_no=<?php echo $rec->purchase_order_id; ?>" class="button"><span class = "icon plus"></span> Make Expense Claim </a>
                        <?php } ?>	  </td>
                </tr>
                <?php
                $num++;
            }
            ?>

        </table>
        <br/>

        <h1>Return Expence Bills </h1>

        <form action="" method="POST">
            <table class="ui-state-default">
                <thead>
                    <tr>
                        <th width='30'>SL.</th>
                        <th width='100'>Expense No </th>
                        <th width='80'>Date</th>
                        <th width='150'>Processing By </th>
                        <th width='100'>Invoice No </th>
                        <th>Vendor</th>
                        <th width="100" align="right">Amount</th>
                        <th width="250" align="center">Return Comments</th>   
                        <th>Action</th>
                        <th>Re-Submit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = query("SELECT exp.PAYMENT_ID, 	   					 
                        DATE_FORMAT(exp.EXPENSE_DATE,'%e-%b, %Y') as date_time,
                        exp.`STATUS`,
                        sup.SUPPLIER_NAME as beneficiary_id

                        FROM fin_payment_approval_note exp
                        LEFT JOIN employee emp on exp.CREATED_BY=emp.EMPLOYEE_ID
                        LEFT JOIN supplier sup on exp.beneficiary_id=sup.SUPPLIER_ID
                        WHERE sup.SUPPLIER_NAME IS NOT NULL");

                    while ($rec = fetch_object($sql)) {
                        $sl++;
                        ?>
                        <tr>
                            <td><?php echo $sl; ?></td>
                            <td><a href="../expense/expense_details2.php?expence_id=<?php echo $rec->expence_id; ?>" target="_blank" ><?php echo $rec->expence_id; ?></a></td>
                            <td><?php echo $rec->date_time; ?></td>
                            <td><?php echo $rec->process_by; ?></td>
                            <td><?php echo $rec->invoice_no; ?></td>
                            <td><?php echo $rec->beneficiary_id; ?></td>
                            <td align="right"><?php echo $rec->amount; ?></td>
                            <td align="left"><?php echo $rec->return_comments; ?></td>
                            <td width="6%" align="center"><?php
                    if ($rec->status < 4) {
                            ?>
                                    <input type="checkbox" name="expence_id[]" value="<?php echo $rec->expence_id; ?>" />
                                <?php } ?>    
                            </td>

                            <td width="6%" align="center"> <a href='expense_bill_re_submit.php?expence_id=<?php echo $rec->expence_id; ?>'>Return</a></div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?> 
                </tbody>
                <tfoot>
                    <tr style="color:#008000; letter-spacing:2px">
                        <td colspan="8"> </td>
                        <td colspan="2"><input type="submit" name="processing" value="Processing" /></td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>

<br/>
<?php include("../body/footer.php"); ?>