<?php
include '../lib/DbManager.php';
include('../body/header.php');

$req_no = getParam('req_no');

$sql = "SELECT si.PRODUCT_ID,
        si.REQUISITION_ID,
        so.REQUISITION_NO,
        pr.PRODUCT_NAME,
        p.PRIORITY_NAME,
        d.DIVISION_NAME,
        so.CREATED_BY, 
        si.REQUISITION_DETAILS_ID,
        CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
        sum(si.QTY) as quantities,
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
        WHERE so.CANCELLED=0 AND so.REQUISITION_NO = '$req_no'
        GROUP BY si.PRODUCT_ID";

$sql_result = query($sql);
// pr.requisition_for=1 and  $sql = query();
?>


<body>

    <div class="easyui-layout" style="width:1100px; height:700px; margin: auto;">  
        <div data-options="region:'center',iconCls:'icon-ok'"> 
            <div  title="Requisition Details" class="easyui-panel"> 
                <form action="add_product_for_cs.php" name="myform" method='GET'>

                    <table class="easyui-datagrid" width="100%">
                        <thead>
                            <tr>
                                <th field="1">SL.</th>
                                <th field="2" width="100">Req.ID</th>
                                <th field="3">Requisition From</th>
                                <th field="4" width="120" align="left">Branch/Dept</th>
                                <th field="8" width="250">Product Name</th>
                                <th field="5" width="80" align="center">Priority</th>
                                <th field="6" width="100" align="right">Req.Qty</th>
                                <th field="7" width="30" align="center">Chk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($rec = fetch_object($sql_result)) {
                                $totall++;
                                ?>

                                <tr>
                                    <td><?php echo $totall . "."; ?></td>
                                    <td align="left"><a href='reco_details.php?reco_id=<?php printf($rec->REQUISITION_ID); ?>' target="_blank"> <?php echo $rec->REQUISITION_NO; ?></a>
                                        <input type='hidden' name='req_id[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->REQUISITION_ID; ?>' />
                                        <input type='hidden' name='product[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]' value='<?php echo $rec->PRODUCT_ID; ?>' /></td> 
                                    <td><?php echo $rec->employeeName; ?></td>
                                    <td><?php echo $rec->branch_dept; ?>  </td>
                                    <td><?php echo $rec->PRODUCT_NAME; ?></td>
                                    <td><?php echo $rec->PRIORITY_NAME; ?></td>
                                    <td><?php echo $rec->quantities - $rec->CS_QTY; ?></td>
                                    <td><input type="checkbox" name="CheckProduct[<?php echo $rec->REQUISITION_DETAILS_ID; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>"/></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <button type="submit" name="btnsupplier" class="button"/><span class = "icon plus"></span>Select Product For CS</button>
                </form>

            </div>
        </div>
    </div>


    <?php include("../body/footer.php"); ?>