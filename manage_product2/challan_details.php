<?php
include '../lib/DbManager.php';
include "ibrahimconvert.php";
$challanid = getParam('challanid');
$requisition = getParam('requisition');

$emp_deta = find("SELECT FIRST_NAME, LAST_NAME, em.ADDRESS, bd.BRANCH_DEPT_NAME, OFFICE_NAME
            FROM employee AS em
            INNER JOIN requisition AS s ON s.CREATED_BY=em.EMPLOYEE_ID
            LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=s.BRANCH_DEPT_ID
            LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=bd.OFFICE_TYPE_ID
            WHERE s.REQUISITION_ID in($requisition)");

$data = find("select challan_no, date_time, branchid, departmentid, createdby, received_by, receive_date from challan where challanid='$challanid'");


list($date, $time) = explode(" ", $data->date_time);

include('../body/header.php');
?>


<div class="easyui-layout" style="width:950px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Delivery Products' style="padding: 10px 10px; background-color:white; "> 

        <fieldset>
            <legend>Challan Details</legend>
            <table class="table">
                <tr>
                    <td align="center"><img src="../public/images/PrimeBank.png" height="40"/></td>
                    <td></td>
                    <td colspan="2" align="right"><span class="style1">Prime Bank Ltd.</span><br />
                        Dead Stock and Stationery Dept<br />
                        <span class="style2">Head Office</span><br />
                        407, Tejgaon 1/A, Dhaka-1208 </td>
                </tr>
                <tr>
                    <td align="left">Date : <?php echo bddate($date); ?> </td>
                    <td></td>
                    <td></td>
                    <td align="left">Created By: <?php echo $emp_deta->FIRST_NAME . ' ' . $emp_deta->LAST_NAME; ?></td>
                </tr>
                <tr>
                    <td width="25%" align="center"><div align="left">Challan No : <font color="#008000"><?php echo $data->challan_no; ?></font></div></td>
                    <td></td>
                    <td></td>
                    <td width="25%" align="center">PR No: 
                        <?php
                        $sql = "SELECT REQUISITION_ID, REQUISITION_NO FROM requisition WHERE REQUISITION_ID IN ($requisition)";
                        $result = query($sql);
                        while ($row = fetch_object($result)) {
                            echo"<a href='reco_details.php?reco_id=$row->REQUISITION_ID' target='_blank'>$row->REQUISITION_NO</a><br/>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td width="25%" align="center">
                        <div align="left">Address : <font color="#008000">
                            <?php echo $emp_deta->address; ?></font>
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                    <td align="center"></td>

                </tr>
            </table>
        </fieldset>


        <table class="ui-state-default">
            <thead>
            <th width="20">SL.</th>
            <th width="100" align="left">Item Code</th>
            <th>Particulars</th>
            <th width="80" align="center">Qty</th>
            <th width="80" align="center">Rate</th>
            <th width="100" align="right">Amount</th>
            </thead>




            <?php
            $sql_list = "select chi.productid,
                pr.PRODUCT_NAME,
                pr.PRODUCT_CODE,
                ut.UNIT_TYPE_NAME,
                chi.uniteprice,
                SUM(chi.quantity) AS quantity,
                ch.createdby,
                sum(IFNULL(chi.uniteprice,0)*IFNULL(chi.quantity,0)) as total
                from challan_item chi
                left join challan ch on ch.challanid = chi.challanid 
                left join product pr on pr.PRODUCT_ID = chi.productid 
                left join unit_type ut on ut.UNIT_TYPE_ID = pr.UNIT_TYPE_ID
                where chi.challanid='$challanid' GROUP BY pr.PRODUCT_ID";
            $sql_result = query($sql_list);

            $sub_total = "";
            while ($rec = fetch_object($sql_result)) {
                $sl++;
                $productid[] = $rec->productid;
                ?>
                <tr>
                    <td align="center" class="sn"><?php echo $sl; ?></td>
                    <td align="left"><?php echo $rec->PRODUCT_CODE; ?></td>
                    <td><?php echo $rec->PRODUCT_NAME; ?></td>
                    <td align='center'><?php echo $rec->quantity; ?></td>
                    <td align='center'><?php echo formatMoney($rec->uniteprice); ?></td>
                    <td align='right'><?php echo formatMoney($rec->total); ?></td>
                </tr>
                <?php
                $sub_total = $sub_total + $rec->total;
            }
            ?>
            <tr class='even'>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td align="center">&nbsp;</td>
                <td>&nbsp;</td>
                <td align='center'><strong>Total</strong></td>
                <td align='right'><?php
                    echo formatMoney($sub_total);

                    $convert = new Ibiconvert();
//echo "My convertion : " . $convert->val($values) . "<br>";

                    list($main, $decimal) = explode(".", $sub_total);
//
                    $tk = $convert->val($main) . " taka ";
//echo $decimal.'--';
                    if ($decimal > 0) {
                        $decimal = $convert->val($decimal);
                        $paisa = "and $decimal paisa ";
                    }
                    ?></td>
            </tr>
            <tr class='even'>
                <td align="center">&nbsp;</td>
                <td colspan="5" align="center"><b>Tk. (In word) :</b>
                    <?php echo $tk . $paisa . "only"; ?></b></td>
            </tr>
            <tr class='even'>
                <td colspan="7" align="left">Delivery Service(Information) : <?php echo findValue("select delivery_info from challan where challanid='$challanid'"); ?></td>
            </tr>
        </table>
        <table>

            <tr>
                <td>
                    <?php
                    echo user_identityById($data->createdby);
                    ?><br />________________<br />
                    Store officer<br>
                    <?php echo bddate($data->date_time); ?></td>

                <td align="right"><?php
                    echo user_identityById($data->received_by);
                    ?><br />______________<br />
                    Received By<br>
                    <?php echo bddate($data->receive_date); ?></td>
            </tr>

        </table>
    </div>
</div>

<?php include '../body/footer.php' ?>



