<?php
include '../lib/DbManager.php';

$CheckProduct = getParam('CheckProduct');
$productid = getParam('product');
$req_id = getParam('req_id');
include('../body/header.php');
?>







<div class="easyui-layout" style="width:1100px; margin: auto; height:600px;">  
    <div data-options="region:'center'" Title='Product List' style="padding: 10px 10px; background-color:white; "> 

        <form action="add_suppliers2.php" method='GET' autocomplete="off" class="formValidate">
            <table class="ui-state-default">
                <thead>
                <th field="name1"  width="30" >SL.</th>
                <th field="name2"  width="100">Req.ID</th>
                <th field="name3">Requisition From</th>
                <th field="name4" width="120">Branch/Dept</th>
                <th field="name11" width="200">Product Name</th>
                <th field="name8"  width="80" align="center">Req Qty</th>
                <th field="name9"  width="80" align="center">CS Qty</th>
                <th field="name10"  width="50" align="center">Select</th>
                </thead>
                <tbody>
                    <?php
                    $sl = 1;
                    foreach ($req_id as $key => $value) {
                        $sql = "SELECT si.PRODUCT_ID,
                        si.REQUISITION_ID,
                        so.REQUISITION_NO,
                        CONCAT(pr.PRODUCT_CODE,'->',pr.PRODUCT_NAME) AS PRODUCT_NAME,
                        p.PRIORITY_NAME,
                        d.DIVISION_NAME,
                        so.CREATED_BY, bd.BRANCH_DEPT_NAME,
                        si.QTY as quantities,
                        (
                            SELECT IFNULL(SUM(cs_qty),0) FROM price_comparison_pro_req_qty pc
                            WHERE requisition_id=si.REQUISITION_ID AND product_id=si.PRODUCT_ID
                        ) AS CS_QTY,
                        CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', de.DESIGNATION_NAME,')')AS 'employeeName'
                        

                        from requisition_details si
                        left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                        left join requisition so on si.REQUISITION_ID=so.REQUISITION_ID
                        LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY                                
                        LEFT JOIN designation de ON de.DESIGNATION_ID=e.DESIGNATION_ID
                        LEFT JOIN priority p ON p.PRIORITY_ID = so.PRIORITY_ID
                        LEFT JOIN division d ON d.DIVISION_ID=so.DIVISION_ID
                        LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
                        LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                        WHERE so.REQUISITION_ID='$req_id[$key]' AND si.PRODUCT_ID = '$productid[$key]' ";
                        $sql_result = query($sql);
                        
                        while ($rec = fetch_object($sql_result)) {
                            ?>

                            <tr>
                                <td><?php echo $sl . "."; ?></td>
                                <td  align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                <td align="left"><?php echo $rec->employeeName; ?></td>
                                <td align="left"><?php echo $rec->DIVISION_NAME . ' ' . $rec->BRANCH_DEPT_NAME; ?>  </td>
                                <td align="left"><?php echo $rec->PRODUCT_NAME; ?></td>
                                <td align="center"><?php echo $rec->quantities; ?></td>
                                <td align="center"><input type="text" name="qty[<?php echo $rec->PRODUCT_ID; ?>][<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->quantities - $rec->CS_QTY; ?>" min="1"/></td>
                                <td align='center'><input type="checkbox" name="CheckProduct[<?php echo $rec->PRODUCT_ID; ?>][<?php echo $rec->REQUISITION_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>"></td>
                            </tr>
                            <?php
                            $sl++;
                        }
                    }
                    ?>
                </tbody>
            </table>

            <input type = "submit" class = "button" value = 'CS Process' name = 'approved' id = "approved" />
        </form>
    </div>
</div>


<?php include("../body/footer.php");
?>