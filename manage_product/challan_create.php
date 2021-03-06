<?php
include '../lib/DbManager.php';
include("../body/header.php");

$search = getParam('search');

$Branch = getParam('Branch');
$Branch = $Branch == 'true' ? 'true' : 'false';

$Divisiton = getParam('Divisiton');
$Divisiton = $Divisiton == 'true' ? 'true' : 'false';

$deptList = $db->rs2array("SELECT bd.BRANCH_DEPT_ID, BRANCH_DEPT_NAME 
FROM branch_dept bd
INNER JOIN requisition r ON r.BRANCH_DEPT_ID=bd.BRANCH_DEPT_ID
WHERE bd.OFFICE_TYPE_ID=1 GROUP BY bd.BRANCH_DEPT_ID");

$branchList = $db->rs2array("SELECT bd.BRANCH_DEPT_ID, bd.BRANCH_DEPT_NAME
                FROM product AS p
                LEFT JOIN app_product_delivery_history AS apdh ON apdh.product_id=p.PRODUCT_ID
                LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                INNER JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                WHERE so.OFFICE_TYPE_ID='2' AND apdh.challan_id IS NULL
                GROUP BY bd.BRANCH_DEPT_ID");


$done = getParam('done');
$quantity = getParam('quantity');
$BranchChallan = getParam('save');


$BranchDeptId = getParam('BranchDeptId');
$BranchDept = getParam('BranchDept');
$req_id = getParam('req_id');


if (isSave()) {

    if ($BranchChallan != 'BranchChallan') {


        foreach ($req_id as $key => $value) {
            $requisition_list .= $value;
        }
        $requisition_list = substr($requisition_list, 0, -1);

        $sql = "insert into challan (requisition_id, branchid, createdby) values('$requisition_list', '1', '$employeeId')";

        sql($sql);
        $challanid = insert_id();
        $challan_no = challan_no($challanid);



        foreach ($done as $key => $value) {

            $uniteprice = $db->findValue("SELECT UNIT_PRICE FROM requisition_details WHERE PRODUCT_ID='$value' AND REQUISITION_ID='$req_id[$key]'");

            $sql_up = ("update stockmove set challanno='$challanid' where PRODUCT_ID='$value'");

            $sql_item = "insert into challan_item (challanid, productid, quantity, uniteprice) 
                    values ('$challanid', '$value', '$quantity[$value]', '$uniteprice')";

            $sal_item = "update requisition_details si set 
                si.STATUS_APP_LEVEL=1,
                si.DETAILS_STATUS=5
                where si.PRODUCT_ID='$value' AND si.REQUISITION_ID='$req_id[$key]'";

            $sql_insert = "INSERT INTO `stockmove` (`PRODUCT_ID`, `qty`, `createdby`, `challanno`) VALUES 
			('$value', '-$quantity[$value]', '$employeeId', '$challanid')";

            $db->sql($sql_up);

            $db->sql($sql_item);
            $db->sql($sal_item);
            $db->sql($sql_insert);
            $db->sql("update challan set challan_no='$challan_no' where challanid='$challanid'");
            $db->sql("update app_product_delivery_history set challan_id='$challanid' where req_id='$req_id[$value]' and product_id ='$value'");
            //echo "update app_product_delivery_history set challan_id='$challanid' where req_id='$req_id[$value]' and product_id ='$value'<br/>";
        }
        //echo "<script>location.replace('challan_create.php?Divisiton=true&mode=');</script>";
    } else {
        foreach ($done as $key => $value) {
            $requisition_list .= $req_id[$key] . ',';
        }
        $requisition_list = substr($requisition_list, 0, -1);

        $sql = "insert into challan (requisition_id, branchid, createdby) values('$requisition_list', '2', '$employeeId')";

        $db->sql($sql);

        $challanid = insert_id();
        $challan_no = challan_no($challanid);



        foreach ($done as $key => $value) {
            $uniteprice = $db->findValue("SELECT UNIT_PRICE FROM requisition_details WHERE PRODUCT_ID='$value' AND REQUISITION_ID='$req_id[$key]'");

            $sql_up = ("update stockmove set challanno='$challanid' where PRODUCT_ID='$value'");

            $sql_item = "insert into challan_item (challanid, productid, quantity, uniteprice) 
                    values ('$challanid', '$value', '$quantity[$key]', '$uniteprice')";


            $sal_item = "update requisition_details si set 
                si.STATUS_APP_LEVEL=1,
                si.DETAILS_STATUS=5
                where si.PRODUCT_ID='$value' AND si.REQUISITION_ID='$req_id[$key]'";


            $sql_insert = "INSERT INTO `stockmove` (PRODUCT_ID, QTY, createdby, challanno) VALUES 
			('$value', '-$quantity[$key]', '$employeeId', '$challanid')";

            $db->sql($sql_item);
            $db->sql($sal_item);
            $db->sql($sql_insert);

            $db->sql($sql_up);
            $db->sql("update challan set challan_no='$challan_no' where challanid='$challanid'");
            $db->sql("update app_product_delivery_history set challan_id='$challanid' where req_id='$req_id[$key]' and product_id ='$value'");
        }
        //echo "<script>location.replace('challan_create.php?Branch=true&mode=');</script>";
    }
}
?>


<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div data-options="region:'center',iconCls:'icon-ok'">  
        <div id="tt" class="easyui-tabs" data-options="fit:true,border:false,plain:true">  

            <div title="Challan Create Divisiton" data-options="selected:<?php echo $Divisiton; ?>">  
                <form action="" method="GET">
                    <input type="hidden" name="Divisiton" value="true"/>
                    Select: <?php comboBox('BranchDept', $deptList, $BranchDept, TRUE); ?>
                    <button class="easyui-linkbutton button" onclick="" iconCls="icon-search" type="submit">Search</button>
                    <button type="button" class="easyui-linkbutton button" iconCls="icon-search" onclick="loadWindow();">Rest</button>
                </form>
                <form name="Challan" action="" method="POST">
                    <table class="ui-state-default" style="width: 100%;">

                        <thead>
                        <th>SL.</th>
                        <th width='100'>Requisition</th>
                        <th width='80'>Product No</th>
                        <th>Product Name</th>
                        <th align="center" width='80'>Qty</th>         
                        <th align="center" width='80'>Action</th>
                        </thead>
                        <?php
                        $sql_challan = "SELECT  p.PRODUCT_CODE, p.PRODUCT_NAME, sum(apdh.delivery_qty) AS del_qty,
                        so.REQUISITION_NO, p.PRODUCT_ID, apdh.req_id, so.REQUISITION_ID, so.BRANCH_DEPT_ID
                        FROM product AS p
                        LEFT JOIN app_product_delivery_history AS apdh ON apdh.product_id=p.PRODUCT_ID
                        LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                        WHERE so.OFFICE_TYPE_ID='1' #AND apdh.challan_id IS NULL AND so.REQUISITION_TYPE_ID='1'
                        AND p.PROCESS_DEPT_ID='$ProcessDeptId' AND BRANCH_DEPT_ID='$BranchDept'
                        GROUP BY apdh.req_id, p.PRODUCT_ID";
                        $sql = $db->query($sql_challan);

                        while ($rec = fetch_object($sql)) {
                            $sl++;
                            $availabe = $rec->stock - $rec->quantities;
                            ?> 
                            <tr>
                                <td align="center"><?php echo $sl; ?></td>
                                <td><a href='../requisition/reco_details.php?reco_id=<?php echo $rec->REQUISITION_ID; ?>' target="_blank"><?php echo $rec->REQUISITION_NO; ?></a></td>
                                <td><?php echo $rec->PRODUCT_CODE; ?></td>
                                <td><?php echo $rec->PRODUCT_NAME; ?></td>
                                <td align="center"><?php echo $rec->del_qty; ?></td>
                                <td align="center">
                                    <input type="hidden" name="BranchDept[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->BRANCH_DEPT_ID; ?>"/>
                                    <input type="hidden" name="req_id[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->REQUISITION_ID; ?>"/>
                                    <input type="checkbox" name="done[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>" />
                                    <input type="hidden" name="orderid[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>"/>
                                    <input type="hidden" name="product[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>"/>
                                    <input type="hidden" name="quantity[<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->del_qty; ?>"/>   
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </table>
                    <button type="submit" class="button" name="save" value="DivisionChallan" >Make Challan</button> 
                </form>
            </div>



            <div title="Challan Create Branch" data-options="selected:<?php echo $Branch; ?>">
                <br/>
                <form action="" method="GET">
                    <input type="hidden" name="Branch" value="true"/>
                    Select: <?php comboBox('BranchDeptId', $branchList, $BranchDeptId, TRUE); ?>
                    <button class="easyui-linkbutton button" onclick="" iconCls="icon-search" type="submit">Search</button>
                    <button type="button" class="easyui-linkbutton button" iconCls="icon-search" onclick="loadWindow();">Rest</button>
                </form>
                <br/>

                <form name="Challan" action="" method="POST">
                    <?php
                    $sql_challan = "SELECT  p.PRODUCT_CODE, p.PRODUCT_NAME, 
                            apdh.delivery_qty AS del_qty,
                            so.REQUISITION_ID, so.REQUISITION_NO, p.PRODUCT_ID, 
                            apdh.req_id, so.REQUISITION_ID, so.BRANCH_DEPT_ID
                            FROM product AS p
                            LEFT JOIN app_product_delivery_history AS apdh ON apdh.product_id=p.PRODUCT_ID
                            LEFT JOIN requisition AS so ON so.REQUISITION_ID=apdh.req_id
                            WHERE so.OFFICE_TYPE_ID='2' AND apdh.challan_id IS NULL AND so.REQUISITION_TYPE_ID='1'
                            AND  p.PROCESS_DEPT_ID='$ProcessDeptId' AND BRANCH_DEPT_ID='$BranchDeptId'";

                    $sql_result = $db->query($sql_challan);
                    ?>
                    
                    <table class="easyui-datagrid">
                        <thead>
                            <tr>
                                <th field="name1">SL.</th>
                                <th field="name7" width='100'>Requisition No</th>
                                <th field="name3" width='100'>Product No</th>
                                <th field="name4">Product Name</th>
                                <th field="name5" align="center" width='100'>Qty</th>         
                                <th field="name6" align="center" width='80'>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysql_fetch_object($sql_result)) {
                                $sl++;
                                $availabe = $row->stock - $row->quantities;
                                ?>

                                <tr>
                                    <td align="center"><?php echo $sl; ?></td>
                                    <td><a href='reco_details.php?reco_id=<?php echo $row->REQUISITION_ID; ?>' target="_blank"><?php echo $row->REQUISITION_NO; ?></a></td>
                                    <td><?php echo $row->PRODUCT_CODE; ?></td>
                                    <td><?php echo $row->PRODUCT_NAME; ?></td>
                                    <td align="center"><?php echo $row->del_qty; ?></td>
                                    <td align="center">
                                        <input type="hidden" name="BranchDept[<?php echo $sl; ?>]" value="<?php echo $row->BRANCH_DEPT_ID; ?>"/>
                                        <input type="hidden" name="req_id[<?php echo $sl; ?>]" value="<?php echo $row->REQUISITION_ID; ?>"/>
                                        <input type="checkbox" name="done[<?php echo $sl; ?>]" value="<?php echo $row->PRODUCT_ID; ?>" />
                                        <input type="hidden" name="orderid[<?php echo $sl; ?>]" value="<?php echo $row->PRODUCT_ID; ?>"/>
                                        <input type="hidden" name="product[<?php echo $sl; ?>]" value="<?php echo $row->PRODUCT_ID; ?>"/>
                                        <input type="hidden" name="quantity[<?php echo $sl; ?>]" value="<?php echo $row->del_qty; ?>"/>   
                                    </td>
                                </tr>
                                <?php
                            }
                            $sl = 0;
                            ?>
                        </tbody>
                    </table>
                    <br/>
                    <button type="submit" class="button" name="save" value="BranchChallan" >Make Challan</button> 
                </form>
            </div>

        </div>  
    </div>
</div>





<?php include("../body/footer.php"); ?>
