

$(document).ready(function() {

    addCombo();
    $('#AjaxUploaderFilesButton').hide().addClass('button');

    $('#AttachmentDetails').keyup(function() {
        var AttachmentDetails = $('#AttachmentDetails').val();
        if (AttachmentDetails.length > 0) {
            $('#AjaxUploaderFilesButton').show();
        }
    });



    $('#attachment_tab .remove').click(function() {
        var val = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: 'ajax_remove_attach_by_id.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();
    });

});

function attach() {
    $('#dlg').removeClass('display_none');

    $('#dlg').dialog('open').dialog('setTitle', 'Attach File');
    $('#fm').form('clear');
    //url = 'product_save.php?mode=new';
}

function CuteWebUI_AjaxUploader_OnTaskComplete(task) {

    var AttachmentDetails = $('#AttachmentDetails').val();
    var k = 1;

    $("<tr>" +
            "<td align='center'>" + k + ".</td>" +
            "<td align='left'>" + AttachmentDetails + "<input type='hidden' value='" + AttachmentDetails + "' name='AttachmentDetails[]'/></td>" +
            "<input type='hidden' value='" + task.FileName + "' name='FileName[]'/>" +
            "<td align='center'><a href='../documents/PR/" + task.FileName + "' class='fancybox'>View </a><div class='remove float-right' onClick='$(this).parent().parent().remove();'><img src='../public/images/delete.png'/></div></td>" +
            "</tr>").appendTo("#attachment_tab");
    k++;
    $('#AjaxUploaderFilesButton').hide();
    $('#AttachmentDetails').val('');

}


//Combo Grid
function addCombo() {
    var requisition_type_id = $('#requisition_type_id').val();
    var processDeptId = $('#processDeptId').val();
    var $table = $("#productGrid");
    var $tableBody = $("tbody", $table);
    var countTr = $('#productGrid tbody tr').length;
    var sl = countTr + 1;

    var newtr = $('<tr>\n\
        <td>' + sl + '</td>\n\
            <td><input type="text" name="productId[]" style="width:500px" class="product required" /></td>\n\
            <td><input style="width:100%" name="qty[]" type="text" class="quantity digit" min="1" value="1"/></td>\n\
            <td><div class="remove" align="center" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

    $tableBody.append(newtr);

    $('.product', newtr).combogrid({
        panelWidth: 400,
        required: true,
        url: 'display_pr_for_store.php?processDept=' + processDeptId,
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
        <td>' + sl + '</td>\n\
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





