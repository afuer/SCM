<?php
include '../lib/DbManager.php';
include "../body/header.php";

///checkPermission(18);
//include('salesorder.inc.php');
$db = new DbManager();
$db->OpenDb();
include('salesorder.inc.php');

$perpage = 20;
$page = getParam('page');
$page = ($page == '') ? 0 : $page;
$page = $page * $perpage;

$status = getParam("status");
$productid = getParam('productid');
$requisition_for = getParam('requisition_for');
$requisition_id = getParam('requisition_id');

$starttime = getParam('start_date');
$starttime = $starttime == '' ? firstDayMonth() : $starttime;
$endtime = getParam('end_date');
$endtime = $endtime == '' ? lasDayMonth() : $endtime;

$del_orderid = getParam("del_orderid");
if (!isEmpty($del_orderid)) {
    cancel_order($del_orderid);
}

//--------------User Define--------------------------		

$emp_row = find("SELECT DEPARTMENT_ID, DIVISION_ID, ul.USER_LEVEL_ID, ul.USER_LEVEL_GROUP_ID, FIRST_NAME, e.EMPLOYEE_ID,
ul.USER_LEVEL_ID, e.EMPLOYEE_ID, DEPARTMENT_ID, DIVISION_ID, ul.USER_LEVEL_ID, FIRST_NAME
FROM  master_user mu
Inner join employee e on e.EMPLOYEE_ID=mu.EMPLOYEE_ID
Inner join user_level ul on ul.USER_LEVEL_ID=mu.USER_LEVEL_ID
where mu.USER_NAME='$user_name'");

$user_division = $emp_row->divisionid;
$employ_level = $emp_row->userlevel;
// echo $employ_level.'----';
$employeeid = $emp_row->employeeid;
$condition = getParam('condition');


$condition ='';

if (getParam("search") == "Search") {

    if ($status == 7) {
        $condition .= " and so.cancelled=1 ";
    } else if ($status != "") {
        $status = $status - 1;
        $condition .= " and so.status=$status and so.cancelled=0 ";
    }
    if ($requisition_for != "") {
        $requisition_for = $requisition_for - 1;
        $requisition_for = " and pr.requisition_for=$requisition_for";
    }

    if ($requisition_id != '') {
        $condition .= " and so.order_no='$requisition_id' ";
    }
}


$delorderid = getParam('delorderid');
if (!isEmpty($delorderid)) {

    sql("update salesorder set cancelled=1  where orderid='$delorderid'");
}



$sql = "SELECT r.REQUISITION_NO,
        r.REQUISITION_ID,
        r.CREATED_BY,
        r.CANCELLED,
        r.PRESENT_LOCATION_ID,
        r.REQUISITION_STATUS_ID,
        pr.PRIORITY_NAME,
        r.REQUISITION_DATE,
        e.FIRST_NAME, 
        rs.status_name

        FROM requisition r 
        INNER JOIN requisition_details rd ON rd.REQUISITION_ID=r.REQUISITION_ID
        LEFT JOIN product p ON p.PRODUCT_ID=rd.PRODUCT_ID
        LEFT JOIN employee e ON e.EMPLOYEE_ID=r.CREATED_BY
        LEFT JOIN priority pr on pr.PRIORITY_ID=r.PRIORITY_ID
        LEFT JOIN requisition_status rs ON rs.requisition_status_id=r.REQUISITION_STATUS_ID
        WHERE r.REQUISITION_DATE BETWEEN '$starttime' AND '$endtime' $condition $requisition_for";

//and cancelled !=0  
if (!isEmpty($productid)) {
    $sql .= " and exists (select * from salesorder_item soi2 where soi2.orderid=so.orderid and soi2.productid='$productid') ";
}
$sql .= " GROUP BY r.REQUISITION_ID ORDER BY r.REQUISITION_NO DESC";
$rs = query($sql);

$status_list = rs2array(query("SELECT * FROM requisition_status"));
$requisition_list = array(array('', 'Store Requisition'), array('2', 'Purchase Requisition'));

include("../body/header.php");
?>

<h2 style="color:#000066; ">Requisition List</h2><br />

<form action="" method="GET">
    <fieldset>
        <table  id="hor-minimalist-b">
            <tr >
                <td width="100" align="left" style="border:0px;"><strong>Req. No.:</strong></td>
                <td><?php textbox("requisition_id", $requisition_id) ?></td>
                <td width="100" ><strong>Status:</strong></td>
                <td><?php comboBox('status', $status_list, $status, TRUE); ?></td>
            </tr>
            <tr>
                <td><strong>Requisition Type: </strong></td>
                <td><?php comboBox('requisition_for', $requisition_list, $requisition_for, TRUE); ?> </td>

                <td width="13%" align="left" style="border:0px;"><strong>Interval From:</strong></td>
                <td width="10%"><input type="text" name="start_date" value="<?php echo $starttime; ?>" /></td>
                <td width="4%" style="border:0px;"><strong>To:</strong></td>
                <td width="73%"><input type="text" name="end_date" value="<?php echo $endtime; ?>" />
            </tr>
        </table>
        <input type="submit" class="button" name="search" value="Search" />
    </fieldset>

    <table id="hor-minimalist-b">

        <tr>
            <th width='25' align="center"><?php etr("S.L") ?></th>
            <th width='12%' align="left"><?php etr("Requisition No") ?></th>
            <th width='16%' align="left"><?php etr("Requisition Date") ?></th>
            <th colspan="3" align="left"><?php etr("Requisition from") ?></th>
            <th width='12%' align="left"><?php etr("Priority") ?></th> 
            <th width='12%' align="left"><?php etr("Present Location") ?></th>           
            <th width='17%' align="left"><?php etr("Status") ?></th> 
            <th colspan="2" align="cenetr"><?php etr("Delete/Edit") ?></th>
        </tr>
        <?php
        $sl = 1;
        $class = "odd";
        $i = 0;
        while ($row = fetch_object($rs)) {

            echo "<tr class='$class'>";
            /*   	deleteColumn("sales.php?del_orderid=$row->orderid");   */
            $script = "salesorder.php";
            if ($row->credit_orgid != null)
                $script = "credit_salesorder.php";
            $href = "$script?orderid=$row->orderid";
            ?>

            <tr><td align=center class='sn'><?php echo $sl . "."; ?></td>
                <td align=left><a href='reco_details.php?reco_id=<?php printf($row->orderid); ?>' target="_blank"><?php printf($row->order_no); ?></a></td>         
                <td align=left>
                    <?php
                    echo
                    bddate(date(DATE_PATTERN, $row->orderdate));
                    ?></td>
                <td colspan="3" align="left"><?php echo user_identity($row->createdby); ?></td> 
                <td  width='12%' align="left"> <?php echo $row->priority; ?> </td> 
                <td  width='15%' align="left"> 
                    <?php
                    if ($row->next_reported_id > 0) {
                        echo $emp_name = findValue("SELECT givenname FROM  employee where employeeid='$row->next_reported_id'");
                    } elseif ($row->next_management_app_level_id > 0) {
                        echo $emp_name = findValue("SELECT level FROM  user_level where levelid='$row->next_management_app_level_id'");
                    } else {
                        echo "Purchase Department";
                    }
                    ?>
                </td> 


                <td  align="left" width=17%><?php echo $row->requisition_name; ?></td> 

                <td align=center width=4%>
                    <?php
                    if ($status == 0):
                        deleteIcon("sales.php?delorderid=$row->orderid");
                    endif;
                    ?></td>
                <td align=center width=4%>
                    <?php
                    echo $status_link = ($status == 0) ? "<a href='salesorder.php?orderid=$row->orderid'><img src='../images/edit.png' title='Edit'></a>" : 'N/A';
                    ?>		 
                </td> 
            </tr>
            <?php
            $class = ($class == "odd" ? "even" : "odd");
            $i++;
            $sl++;
        }
        ?>  
        <tr>
            <td align=center >&nbsp;</td>
            <td colspan="3" align=center>&nbsp;</td>
            <td width="3%">&nbsp;</td>
            <td width="21%" align=center>&nbsp;</td>
            <td align=center>&nbsp;</td>
            <td align=center>&nbsp;</td>
            <td align=center>&nbsp;</td>
            <td align=center>&nbsp;</td>
        </tr>
        <?php
        if (isSave()) {
            $id = getParam("id");
            $rec_id = getParam("rec_id");
            if (!empty($id)) {

                $done = getParam("done");
                foreach ($id as $key => $value) {
                    if (!empty($done[$key])) {
                        $approval_level = findValue("select levelid from employee where employeeid=$employeeid");

                        $sql_up = "update salesorder set approval_level=$employ_level where orderid=$rec_id[$key]";
                        sql($sql_up);

                        $no = findValue("select max(no) from approval_recoquisition where reco_id=$rec_id[$key]");
                        $no++;

                        //------------------------
                        $sals_query = query("select orderid, productid, quantity from salesorder_item where orderid=$rec_id[$key]");

                        while ($rec = fetch($sals_query)) {

                            $sql_ins = "insert into approval_recoquisition (reco_id, productid, user_id, no, quantity)
				values($rec->orderid, $rec->productid, $employeeid, $no, $rec->quantity)";
                            sql($sql_ins);
                        }
                    }
                }
            }
            echo "<script>location.replace('all_requisition_list.php')</script>";
        }
        $db->CloseDb();
        ?>
    </table>

</form>

<?php include("../body/footer.php"); ?>