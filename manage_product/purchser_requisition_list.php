<?php
include '../lib/DbManager.php';


$sendForCS = getParam('sendForCS');



include("../body/header.php");
?>

<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div data-options="region:'center',iconCls:'icon-ok'">  
        <div id="tt" class="easyui-tabs" data-options="fit:true,border:false,plain:true">  

            <div  title="Opex Requisition List ">  
                <div class="easyui-panel"  title="New Requisition List">  

                    <table class="easyui-datagrid">
                        <thead>
                            <tr>
                                <th field="name1" align="center">SL.</th>
                                <th field="name2"width='100'>Requisition No</th>
                                <th field="name7">Req Date</th>
                                <th field="name5">Branch/Dept</th>
                                <th field="name3">Requisition From</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //$ProcessDeptId
                            $PurchaseSql = "SELECT pr.PRODUCT_CODE,
                                si.PRODUCT_ID, si.REQUISITION_ID, 
                                so.REQUISITION_NO,
                                max(so.PRIORITY_ID) as PRIORITY_ID,
                                pr.PRODUCT_NAME, DATE_FORMAT(so.REQUISITION_DATE,'%e %b %Y') AS REQUISITION_DATE,
                                sum(IFNULL(si.QTY,0)) as quantities,
                                SUM(IFNULL(si.CS_QTY,0)) AS CS_QTY,
                                CONCAT(ot.OFFICE_NAME,'->',bd.BRANCH_DEPT_NAME) AS branch_dept,
                                CONCAT(e.CARD_NO,'->', e.FIRST_NAME,' ',e.LAST_NAME, ' (', d.DESIGNATION_NAME,')')AS 'employeeName'

                                from requisition_details si
                                left join requisition so on so.REQUISITION_ID=si.REQUISITION_ID
                                left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                                LEFT JOIN branch_dept bd ON bd.BRANCH_DEPT_ID=so.BRANCH_DEPT_ID
                                LEFT JOIN office_type ot ON ot.OFFICE_TYPE_ID=so.OFFICE_TYPE_ID
                                LEFT JOIN employee e ON e.EMPLOYEE_ID=so.CREATED_BY                                
                                LEFT JOIN designation d ON d.DESIGNATION_ID=e.DESIGNATION_ID
                                WHERE so.CANCELLED=0 AND so.REQUISITION_TYPE_ID=2 
                                AND pr.PRODUCT_GROUP_ID=1 AND so.REQUISITION_STATUS_ID<>6
                                AND so.USER_LEVEL_ID ='$UserLevelId' AND so.PROCESS_DEPT_ID LIKE'%$ProcessDeptId%'
                                GROUP BY so.REQUISITION_ID HAVING quantities-CS_QTY>0
                                ORDER BY si.REQUISITION_ID DESC";

                            $PurchaseResult = query($PurchaseSql);
                            //PRODUCT_TYPE_ID=1 and so.PROCESS_DEPT_ID='1' AND and si.status_app_level=0

                            $count = 0;
                            while ($rec = fetch_object($PurchaseResult)) {
                                $count++;
                                ?> 
                                <tr>
                                    <td><?php echo $count; ?>.</td>
                                    <td align="left"><a href="approve_pr_product.php?req_no=<?php echo $rec->REQUISITION_NO; ?>&condition=<?php echo "product_type=0 and"; ?>" target="_blank"><?php echo $rec->REQUISITION_NO; ?></a></td>
                                    <td align="left"><?php echo $rec->REQUISITION_DATE; ?></td>
                                    <td align="center"><?php echo $rec->branch_dept; ?></td>
                                    <td align="left"><?php echo $rec->employeeName; ?></td>
                                    <?php
                                }
                                ?>
                        </tbody>
                    </table>

                </div>
            </div>





            <div title="Product Requisition List of Capex">
                <form action="add_suppliers2.php" name="myform" method="POST">

                    <table class="ui-state-default" style="width:100%">
                        <thead>
                        <th width='20' align="center">Sl.</th>
                        <th width='100' align="left">Product No</th>
                        <th>Product Name</th>
                        <th width='100' align="center">Priority</th>         
                        <th width='100' align="center">Requisition Qty</th>         
                        <th width="50" align="right">Action</th>
                        </thead>
                        <?php
                        $PurchaseSql2 = "SELECT pr.PRODUCT_CODE,
                            si.PRODUCT_ID, si.REQUISITION_ID, so.REQUISITION_NO,
                            max(so.PRIORITY_ID) as PRIORITY_ID,
                            pr.PRODUCT_NAME, sum(si.QTY) as quantities
                            

                            from requisition_details si
                            left join requisition so on so.REQUISITION_ID=si.REQUISITION_ID
                            left join product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                            where  so.CANCELLED=0 AND so.REQUISITION_TYPE_ID=2 
                            and si.DETAILS_STATUS=1 AND pr.PRODUCT_GROUP_ID=2
                            AND si.status_app_level!=1 AND so.USER_LEVEL_ID IS NOT NULL
                            group by si.PRODUCT_ID order by si.REQUISITION_ID";
                        $sql2 = query($PurchaseSql2);

                        //PRODUCT_TYPE_ID=1 and so.PROCESS_DEPT_ID='1' AND and si.status_app_level=0

                        $count2 = 0;
                        while ($rec = fetch_object($sql2)) {
                            $count2++;
                            ?> 
                            <tr>
                                <td><?php echo $count2; ?>.</td>
                                <td align="left"><?php echo $rec->PRODUCT_CODE; ?></td>
                                <td align="left"><?php echo $rec->PRODUCT_NAME; ?></td>
                                <td align="center"><?php echo star_sign($rec->PRIORITY_ID); ?> </td>
                                <td align="center"><a href="approve_pr_product.php?productid=<?php echo $rec->PRODUCT_ID; ?>&condition=<?php echo " product_type=2 and requisition_routeid = $requisition_routeid and"; ?>" target="_blank"><?php echo $rec->quantities; ?></a></td>
                                <td align="right"/>
                            <input type="checkbox" name="chkproduct00[]" value="<?php echo $rec->PRODUCT_ID . '~' . $rec->quantities; ?>"/>
                            <input type="hidden" name="req[<?php echo $rec->PRODUCT_ID; ?>]" id="req[<?php echo $rec->REQUISITION_NO; ?>]" value="<?php echo $rec->REQUISITION_NO; ?>"/>
                            <input type="hidden" name="record[<?php echo $rec->PRODUCT_ID; ?>]"/>
                            <input type="hidden" name="product[<?php echo $rec->PRODUCT_ID; ?>]" id="product[<?php echo $rec->REQUISITION_NO; ?>]" value="<?php echo $rec->PRODUCT_ID; ?>"/>
                            <input type="hidden" name="branchid2" value="<?php echo $rec->branchid; ?>"/>
                            <input type="hidden" name="quantity[<?php echo $rec->PRODUCT_ID; ?>]" value="<?php echo $rec->quantities; ?>"/>  </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <button type="submit" name = "btnsupplier" class="button"/><span class = "icon plus"></span>Create Comparative Statement</button>

                    <input type="hidden" name="action" id="action" value="new"/>
                </form>
            </div>

            <div title="At Actual">
                <form action="add_suppliers2.php" name="myform" method=post>

                    <table class="ui-state-default" style="width:100%">
                        <thead>
                        <th width='20'>Sl.</th>
                        <th width='100'>Product No</th>
                        <th>Product Name</th>
                        <th></th>
                        <th></th>
                        </thead>
                        <?php
                        $sql = query("SELECT pr.PRODUCT_ID,
                        pr.PRODUCT_CODE, si.REQUISITION_ID,
                        max(so.PRIORITY_ID) as priorityid,
                        pr.PRODUCT_NAME,
                        sum(si.QTY) as quantities

                        from requisition_details si
                        left join requisition so on so.REQUISITION_ID = si.REQUISITION_ID
                        left join product pr on si.PRODUCT_ID = pr.PRODUCT_ID
                        where si.DETAILS_STATUS = 1 and si.STATUS_APP_LEVEL = '-1' AND so.CANCELLED = 0
                        AND pr.at_actual = 1 group by si.PRODUCT_ID
                        HAVING quantities>0 order by si.REQUISITION_ID");



                        $sl = 1;
                        while ($rec = mysql_fetch_object($sql)) {
                            //$pending = $rec->quantities - $rec->deliverd;
                            ?> 
                            <tr>
                                <td class="sn" align="center"><?php echo $sl; ?>.</td>
                                <td align="left"><?php echo $rec->PRODUCT_CODE; ?></td>
                                <td  align="left"><?php echo $rec->PRODUCT_NAME; ?></td>
                                <td align="center" ></td>
                                <td align="center"><a href="approve_at_actual_product.php?productid = <?php echo $rec->PRODUCT_ID; ?>&condition=<?php echo "product_type=2 and requisition_routeid=$requisition_routeid and"; ?>" target="_blank"><?php echo $rec->quantities; ?></a></td>
                            </tr> 
                            <?php
                            $sl++;
                        }
                        ?>

                    </table>
                </form>
            </div>

        </div>  
    </div>
</div>



<?php
include("../body/footer.php");
?>