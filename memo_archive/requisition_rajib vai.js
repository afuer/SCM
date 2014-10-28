

$(document).ready(function() {

    addTableTr();
    addCombo();

    $('#productGrid .remove').click(function() {
        var val = $(this).attr('id');
        //console.log(val);
        $.ajax({
            type: "POST",
            url: 'ajax_remove_product_by_id.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();
        RecalcProduct();

    });

    var k = 1;
    var AttachmentDetails = '';
    $('#file_upload').uploadify({
        // Some options
        'onSelect': function() {
            AttachmentDetails = $('#AttachmentDetails').val();
            if (AttachmentDetails == '') {
                AttachmentDetails = 'Title';
                alert('Please Enter The Tittle Of Attachment');
                //return; 
            }
            //alert('The file ' + file.name + ' was added to the queue.');
        },
        'method': 'post',
        'formData': {
            'id': '1'
        },
        'uploader': 'uploadify.php',
        'buttonClass': 'uploadify-button',
        'buttonText': 'Browse File',
        'onUploadSuccess': function(file, data, response) {
            $('#file_upload_done').val(data);
            $('#file_upload_done').removeClass('text_field_display')
            $('#file_upload').removeClass('uploadify-button').css('display', 'none');
            var FileName = $('#file_upload_done').val();
            $("<tr>" +
                    "<td align='center'>" + k + ".</td>" +
                    "<td align='left'>" + AttachmentDetails + "<input type='hidden' value='" + AttachmentDetails + "' name='AttachmentDetails[]'/></td>" +
                    "<input type='hidden' value='" + FileName + "' name='FileName[]'/>" +
                    "<td align='center'><a href='" + FileName + "' class='fancybox'>View </a><div class='remove float-right' onClick='$(this).parent().parent().remove();'><img src='../public/images/delete.png'/></div></td>" +
                    "</tr>").appendTo("#attachment_tab");
            k++;
            $('#file_upload_done').addClass('text_field_display')
            $('#file_upload').addClass('uploadify-button').css('display', '');
        }
    });

    $('#file_upload_done').css('display', 'none');



    $('#AttachmentDetails').keyup(function() {
        SelectShowHide();
    });
    SelectShowHide();

    function SelectShowHide() {

        var selectFile = $('#AttachmentDetails').val().length;

        if (selectFile > 0) {
            $('#file_upload').show();
        } else {
            $('#file_upload').hide();
        }

    }



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



function RecalcProduct() {
    $("[id^=TotalPrice]").calc(
            // the equation to use for the calculation
            "qty * price",
            // define the variables used in the equation, these can be a jQuery object
                    {
                        qty: $("input[name^=qty]"),
                        price: $("[id^=price_product]")
                    },
            // define the formatting callback, the results of the calculation are passed to this function
            function(s) {
                // return the number as a dollar amount
                return s.toFixed(2);
            },
                    // define the finish callback, this runs after the calculation has been complete
                            function($this) {
                                // sum the total of the $("[id^=total_item]") selector
                                var sum = $this.sum();

                                $("#ProductGrantTotal").text(
                                        // round the results to 2 digits
                                        sum.toFixed(2)
                                        );
                            });
                }




        //Combo Grid
        function addCombo() {
            var requisition_type_id = $('#requisition_type_id').val();
            var processDeptId = $('#processDeptIdID').val();
            var $table = $("#productGrid");
            var $tableBody = $("tbody", $table);
            var countTr = $('#productGrid tbody tr').length;
            var sl = countTr + 1;

            var newtr = $('<tr>\n\
        <td>' + sl + '</td>\n\
            <td><input type="text" name="employeeID[]" style="width:500px" class="employee required" /></td>\n\
            <td><input style="width:100%" name="approvalTypes[]" type="text" class="quantity digit" min="1" value="1"/></td>\n\
            <td><div class="remove" align="center" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

            $tableBody.append(newtr);

            $('.employee', newtr).combogrid({
                panelWidth: 400,
                url: 'dispalyEmployee.php',
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

            var $table = $("#productTab");
            var $tableBody = $("tbody", $table);
            var countTr = $('#productTab tbody tr').length;
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





