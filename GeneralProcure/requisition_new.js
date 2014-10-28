
$(document).ready(function() {

    addtr();


});

function test() {

    $("#test").autocomplete({
        source: 'autocomplate_search_product.php',
        minLength: 2,
        select: function(evt, ui)
        {
            $('#processDept').val(ui.item.processDept);
            $('#product').val(ui.item.productId);
            $('#unit').html(ui.item.unit);
            $('#price').text(ui.item.price);
        }
    });


}



function attach() {
    $('#dlg').removeClass('display_none');

    $('#dlg').dialog('open').dialog('setTitle', 'Attach File');
    $('#fm').form('clear');
    //url = 'product_save.php?mode=new';
}



function addTabletr() {
    var newtr = $('<tr>\n\
            <td valign="top"><select class="product" name="product[]" placeholder="Product" style="width:100%">' + $('#productID').html() + '</select></td>\n\
            <td valign="top"><input style="width:100px" name="qty[]" type="text" class="quantity digit" min="1" value="1"  onkeyup="RecalcProduct()" /><label class="unit"></label></td>\n\
            <td valign="top"><input style="width:100%" name="price[]" type="text" class="price number" id="price_product"  onkeyup="RecalcProduct()" /></td>\n\
            <td valign="top" id="TotalPrice" align="right">00.00 </td>\n\
            <td valign="top"><input style="width:100%" name="remark[]" type="text" /></td>\n\
            <td valign="top"><div class="remove float-right" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

    $('#productGrid tbody').append(newtr);
    allAutocomplate();
}


//Combo Grid
function addtr() {
    //var countTr = $('#productGrid tbody tr').length;
    //var sl = countTr + 1;

    var newtr = $('<tr>\n\
            <td valign="top"><input type="text" name="productid[]" style="width:100%" class="productid required" />\n\
            <input type="hidden" name="product[]" class="product" /></td>\n\
            <input type="hidden" name="processDeptId[]" class="processDept" /></td>\n\
            <td valign="top"><input style="width:80px" name="qty[]" type="text" class="quantity digit" min="1" value="1"  onkeyup="RecalcProduct()" /><label class="unit"></label></td>\n\
            <td valign="top"><input style="width:100%" name="price[]" type="text" class="price number" id="price_product"  onkeyup="RecalcProduct()" /></td>\n\
            <td valign="top" id="TotalPrice" align="right">00.00 </td>\n\
            <td valign="top"><input style="width:100%" name="remark[]" type="text" /></td>\n\
            <td valign="top"><div class="remove" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');
    var requisition_type_id = $('input[name="requisition_type_id"]').val();
    //var processDeptId = $('input[name="processDeptId"]').val();


    $('.productid', newtr).autocomplete({
        source: 'autocomplate_search_product.php?requisition_type_id=' + requisition_type_id,
        minLength: 2,
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





