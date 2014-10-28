<?php
include '../lib/DbManager.php';
include '../body/header.php';
/*
  function stock($productid) {
  $value = findValue("select sum(QTY) as stock from stockmove where PRODUCT_ID='$productid'");
  return $value;
  }

  function delivery($productid) {
  $value = findValue("select sum(delivery_qty) as delivery from app_product_delivery_history where product_id='$productid' and receipt_by=0 ");
  return $value;
  }

  function allocated($productid, $pr_for_store_item) {
  $value = findValue("SELECT sum(si.QTY) as allocated
  FROM requisition_details si
  LEFT JOIN requisition so on si.REQUISITION_ID=so.REQUISITION_ID
  WHERE si.PRODUCT_ID='$productid' and so.REQUISITION_STATUS_ID < 5 and REQUISITION_TYPE_ID='$pr_for_store_item'
  GROUP BY si.PRODUCT_ID");
  return $value;
  }
 * 
 */
?>

<div class="easyui-layout" style="width:950px; height:700px; margin: auto;">  
    <div title="Requested Pending Product List" data-options="region:'center'" class="easyui-panel" >  

        <form action="reorder_po.php" name="myform" method=post>
            <h2>Product Requisition List of Opex </h2>
            <table  width="100%"  class="ui-state-default">
                <thead>
                    <tr>
                        <th width='20' align="center">Sl</th>
                        <th width='100' >Product No</th>
                        <th>Product Name</th>
                        <th width='100' align="center">Requisition Qty</th>         
                        <th width='100'>Action</th>
                    </tr>
                </thead>
                <?php
                $sql = query("SELECT 
                pr.PRODUCT_CODE,
                si.PRODUCT_ID,
                si.REQUISITION_ID,
                pr.PRODUCT_NAME,
                sum(si.QTY) as quantities

                FROM requisition_details si
                LEFT JOIN product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                WHERE si.DETAILS_STATUS=3 and si.STATUS_APP_LEVEL=1 
                GROUP BY si.PRODUCT_ID ORDER BY si.REQUISITION_ID");

                //where product_type=1  and requisition_for=1 and requisition_routeid = '$requisition_routeid'

                $count = 0;
                while ($rec = fetch_object($sql)) {
                    $count++
                    ?> 
                    <tr>
                        <td><?php echo $count . "."; ?></td>
                        <td align="center"><?php echo $rec->PRODUCT_CODE; ?></td>
                        <td><?php echo $rec->PRODUCT_NAME; ?></td>

                        <td align="center"><a href="../manage_product/approve_pr_product.php?productid=<?php echo $rec->PRODUCT_ID; ?>&condition=<?php echo "product_type=0 and requisition_routeid = $requisition_routeid and"; ?>" target="_blank" ><?php echo $rec->quantities; ?></a></td>
                        <td align="center">
                            <input type="checkbox" name="chkproduct00[]" value="<?php echo $rec->productid . '~' . $rec->quantities; ?>">
                            <input type="hidden" name="req[<?php echo $rec->productid; ?>]" id="req[<?php echo $rec->orderid; ?>]" value="<?php echo $rec->orderid; ?>">
                            <input type="hidden" name="record[<?php echo $rec->productid; ?>]">
                            <input type="hidden" name="product[<?php echo $rec->productid; ?>]" id="product[<?php echo $rec->orderid; ?>]" value="<?php echo $rec->productid; ?>">
                            <input type="hidden" name="branchid2" value="<?php echo $rec->branchid; ?>">
                            <input type="hidden" name="quantity[<?php echo $rec->productid; ?>]" value="<?php echo $rec->quantities; ?>">  </td>
                        <td width="7%"></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td  align="right" colspan="6">
                        <button type="submit" name = "btnsupplier" class="button"/><span class = "icon plus"></span>Reorder PO</button>
                    </td>
                </tr>


            </table>

            <div>Product Requisition List of Capex </div>
            <table width="100%" class="ui-state-default">
                <thead>
                    <tr>
                        <th width='20' align="center">Sl</th>
                        <th width='17%' align="center">Product No</th>
                        <th width='33%' align="left">Product Name</th>
                        <th width='36%' align="center">Requisition Qty</th>         
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <?php
                $sql = query("SELECT 
                pr.PRODUCT_CODE,
                si.PRODUCT_ID,
                si.REQUISITION_ID,
                pr.PRODUCT_NAME,
                sum(si.QTY) as quantities

                FROM requisition_details si
                LEFT JOIN product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                WHERE si.DETAILS_STATUS=3 and si.STATUS_APP_LEVEL=1 
                GROUP BY si.PRODUCT_ID ORDER BY si.REQUISITION_ID");



                $count2 = 0;
                $class = "odd";
                while ($rec = fetch_object($sql)) {
                    $count2++
                    ?> 
                    <tr  <?php echo "class='$class'"; ?>>
                        <td class="sn"><?php echo $count2 . "."; ?></td>
                        <td align="center"><?php echo $rec->PRODUCT_CODE; ?></td>
                        <td align="left"><?php echo $rec->PRODUCT_NAME; ?></td>

                        <td align="center"><a href="approve_pr_product.php?productid=<?php echo $rec->productid; ?>&condition=<?php echo " product_type=2 and requisition_routeid = $requisition_routeid and"; ?>"><?php echo $rec->quantities; ?></a></td>
                        <td width="4%" align="center">
                            <input type="checkbox" name="chkproduct00[]" value="<?php echo $rec->productid . '~' . $rec->quantities; ?>">
                            <input type="hidden" name="req[<?php echo $rec->productid; ?>]" id="req[<?php echo $rec->orderid; ?>]" value="<?php echo $rec->orderid; ?>">
                            <input type="hidden" name="record[<?php echo $rec->productid; ?>]">
                            <input type="hidden" name="product[<?php echo $rec->productid; ?>]" id="product[<?php echo $rec->orderid; ?>]" value="<?php echo $rec->productid; ?>">
                            <input type="hidden" name="branchid2" value="<?php echo $rec->branchid; ?>">
                            <input type="hidden" name="quantity[<?php echo $rec->productid; ?>]" value="<?php echo $rec->quantities; ?>">  </td>
                        <td width="7%"></td>
                    </tr>
                    <?php
                    $class = ($class == "odd" ? "even" : "odd");
                }
                ?>
            </table>

            <div>Requisition List of Pending Item(s)</div>
            <table  width="100%"   class="ui-state-default">
                <thead>
                    <tr>
                        <th width='20' align="center">Sl</th>
                        <th width='17%' align="center">Product No</th>
                        <th width='33%' align="left">Product Name</th>
                        <th width='36%' align="center">Requisition Qty</th>         
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <?php
                $sql_pend = query("SELECT 
                pr.PRODUCT_CODE,
                si.PRODUCT_ID,
                si.REQUISITION_ID,
                pr.PRODUCT_NAME,
                sum(si.QTY) as quantities

                FROM requisition_details si
                LEFT JOIN product pr on si.PRODUCT_ID=pr.PRODUCT_ID
                WHERE si.DETAILS_STATUS=3 and si.STATUS_APP_LEVEL=1 
                GROUP BY si.PRODUCT_ID ORDER BY si.REQUISITION_ID");



                $count2 = 0;
                $class = "odd";
                while ($rec_pen = fetch_object($sql_pend)) {
                    $count2++
                    ?> 
                    <tr>
                        <td class="sn"><?php echo $count2 . "."; ?></td>
                        <td align="center"><?php echo $rec_pen->PRODUCT_CODE; ?></td>
                        <td><?php echo $rec_pen->PRODUCT_NAME; ?></td>

                        <td align="center"><a href="approve_pr_product2.php?productid=<?php echo $rec_pen->productid; ?>&condition=<?php echo " product_type=2 and requisition_routeid = $requisition_routeid and"; ?>" ><?php echo $rec_pen->quantities; ?></a></td>
                        <td width="4%" align="center">&nbsp; </td>
                        <td width="7%">&nbsp;</td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td  align="right" colspan="6">
                        <button type="submit" name = "btnsupplier" class="button"/><span class = "icon plus"></span>Reorder PO </button>  
                    </td>
                </tr>
                <input type="hidden" name="action" id="action" value="new">
                <input type="hidden" name="parent_url" id="parent_url" value="<?php echo $_SERVER['REQUEST_URI'] ?>">
            </table>
        </form>

    </div>
</div>
<?php include("../body/footer.php"); ?>