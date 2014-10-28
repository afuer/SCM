
$(document).ready(function() {
    addtr();
});

function addtr() {
    //var countTr = $('#productGrid tbody tr').length;
    //var sl = countTr + 1;

    var newtr = $('<tr>\n\
            <td valign="top"><input type="text" name="product[]" style="width:100%" class="productid required" />\n\
            <input type="hidden" name="productId[]" class="product" /></td>\n\
            <input type="hidden" name="processDeptId[]" class="processDept" /></td>\n\
            <td valign="top"><input style="width:80px" name="qty[]" type="text" class="quantity digit" min="1" value="1"  onkeyup="RecalcProduct()" /><label class="unit"></label></td>\n\
            <td valign="top"><input style="width:100%" name="remark[]" type="text" /></td>\n\
            <td valign="top"><div class="remove" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');
    var requisition_type_id = $('input[name="requisition_type_id"]').val();
    var processDeptId = $('input[name="processDept"]').val();


    $('.productid', newtr).autocomplete({
        source: 'autocomplate_search_product.php?requisition_type_id=' + requisition_type_id + '&processDeptId=' + processDeptId,
        minLength: 1,
        select: function(evt, ui)
        {
            var itemrow = $(this).closest('tr');
            itemrow.find('.processDept').val(ui.item.processDept);
            itemrow.find('.product').val(ui.item.productId);
            itemrow.find('.unit').html(ui.item.unit);
            itemrow.find('.price').text(ui.item.price);
            // when a zipcode is selected, populate related fields in this form
            //this.form.table.qty.value = ui.item.Under;
            //this.form.state.value = ui.item.Nature;
        }
    });
    $('#productGrid tbody').append(newtr);
}


//Combo Grid
function addCombo() {
    var requisition_type_id = $('#requisition_type_id').val();
    var processDeptId = $('#processDeptId').val();
    var $table = $("#productGrids");
    var $tableBody = $("tbody", $table);
    var countTr = $('#productGrids tbody tr').length;
    var sl = countTr + 1;

    var newtr = $('<tr>\n\
        <td>' + sl + '.</td>\n\
            <td><input type="text" name="productId[]" style="width:500px" class="product required" /></td>\n\
            <td><input style="width:100%" name="qty[]" type="text" class="quantity digit" min="1" value="1"/></td>\n\
            <td><div class="remove" align="center" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

    $tableBody.append(newtr);

    $('.product', newtr).combogrid({
        panelWidth: 400,
        required: true,
        url: 'display_item_stock.php?processDept=' + processDeptId + '&requisition_type_id=' + requisition_type_id,
        idField: 'PRODUCT_ID',
        textField: 'PRODUCT_NAME',
        mode: 'remote',
        fitColumns: true,
        columns: [[
                {field: 'PRODUCT_CODE', title: 'Item Code', width: 100},
                {field: 'PRODUCT_NAME', title: 'Product Name', width: 200},
                {field: 'UNIT_TYPE_NAME', title: 'Unit Name', width: 80}
            ]]
    });
}




//Auto Complate
function addTableTr() {

    var $table = $("#productGrid");
    var $tableBody = $("tbody", $table);
    var countTr = $('#productGrid tbody tr').length;
    var sl = countTr + 1;

    var newtr = $('<tr>\n\
        <td>' + sl + '.</td>\n\
            <td><input type="text" name="productName[]" style="width:100%" class="product required" /></td>\n\
            <td><input style="width:100%" name="qty[]" type="text" class="quantity digit" min="1" value="1" onchange="calCulate();"/></td>\n\
            <td><input style="width:100%" name="price[]" type="text" class="price number" id="price_product" value="" onchange="calCulate();"/></td>\n\
            <td id="TotalPrice" align="right">00.00 </td>\n\
            <td><input style="width:100%" name="remark[]" type="text" /></td>\n\
            <td><div class="remove" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

    $('.product', newtr).autocomplete(autocomp_opt);
    $tableBody.append(newtr);
}


var autocomp_opt = {
    source: function(request, response) {
        $.ajax({
            url: "product_get_search.php",
            dataType: "json",
            type: "post",
            data: {
                maxRows: 15,
                term: request.term
            },
            success: function(data) {
                response($.map(data, function(item) {
                    return {
                        label: item.ProductName,
                        value: item.ProductName
                    }
                }))
            }
        })
    },
    minLength: 2
};





