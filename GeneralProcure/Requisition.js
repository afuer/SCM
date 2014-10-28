$(document).ready(function() {

attachComboGrid();


    $('#productTab .remove').click(function() {
        var val = $(this).attr('id');
        //console.log(val);
        $.ajax({
            type: "POST",
            url: 'ajax_remove_product_by_id.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();

    });

    $('#cost_center .remove').click(function() {
        var val = $(this).attr('id');
        //console.log(val);
        $.ajax({
            type: "POST",
            url: 'ajax_remove_cc_by_id.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();

    });


    $('#WorkflowTab .remove').click(function() {
        var val = $(this).attr('id');
        //console.log(val);
        $.ajax({
            type: "POST",
            url: 'AjaxRemoveWorkFlow.php?val=' + val,
            success: function(data) {
            }
        });
        $(this).parent().parent().remove();

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


    $('.quantity').change(function() {
        RecalcProduct();
    });


    $('.price').change(function() {
        RecalcProduct();
    });

    $('input:radio[name=Workflow]').live('change', function() {
        WorkflowType = $('input:radio[name=Workflow]:checked').val();

        if (WorkflowType == 1) {
            $.ajax({
                type: "GET",
                url: 'AjaxManualWorkFlow.php',
                success: function(data) {

                    $('#AjaxManualWorkFlow').html(data);

                }
            });
            $('#WorkflowDefault').html('');
            $('#WorkflowTab_defult').html('');

        } else {
            $.ajax({
                type: "GET",
                url: 'AjaxWorkFlowGroup.php',
                success: function(data) {

                    $('#WorkflowDefault').html(data);

                }
            });

            $('#AjaxManualWorkFlow').html('');

        }
    });







    ///END  ADD PRODUCT ////

    //ADD CostCenter
    var i = 1, TotalCost = 0, j = 1, k = 1, account_type, WorkflowType;

    //$('.CostcenterPercent').attr('disabled', 'disable');

    $('.account_type').change(function() {
        account_type = $(this).val();

        if (account_type == 1) {
            $('.CostcenterPercent').attr('disabled', 'disable');
        } else {
            $('.CostcenterPercent').removeAttr('disabled');
        }

    });


    //// ccc
    $('.CostcenterPercent').change(function() {

        var CostcenterPercent, CostcenterAmount, budget, itemrow;
        budget = $('#ProductGrantTotal').text();
        CostcenterPercent = parseFloat($(this).val()).toFixed(2);
        itemrow = $(this).closest('tr');

        CostcenterAmount = parseFloat((budget * CostcenterPercent) / 100).toFixed(2);

        itemrow.find("input[name='CostcenterAmount[]']").val(CostcenterAmount);
        recalc(1);

        var CCTotal = parseFloat($('#TotalCost').text());
        if (budget < CCTotal) {
            alert('Cost center Total is not allowed to greater than Product Total');
            itemrow.find("input[name='CostcenterAmount[]']").val(0);
            itemrow.find("input[name='CostcenterPercent[]']").val(0);
            recalc(1);
        }



    });

    $('.CostcenterAmount').change(function() {


        var CostcenterPercent, budget;
        budget = $('#ProductGrantTotal').text();
        CostcenterPercent = $(this).val();
        CostcenterAmount = parseFloat((budget * CostcenterPercent) / 100).toFixed(2);
        recalc(1);

        var ProductTotal, CCTotal;
        CCTotal = parseFloat($('#TotalCost').text());
        ProductTotal = parseFloat($('#ProductGrantTotal').text());
        if (ProductTotal < CCTotal) {
            var differ = CCTotal - ProductTotal;
            alert('Cost center Total is not allowed to greater than Product Total');
            var CurrentVal = $(this).val();
            var ActualAmount = CurrentVal - differ;
            $(this).val(ActualAmount);
        }






    });

    var sum;
    function recalc(quantity) {
        $("[id^=total_item]").calc(
                // the equation to use for the calculation
                "qty * price",
                // define the variables used in the equation, these can be a jQuery object
                        {
                            //qty: $("input[name^=CostcenterPercent]")
                            qty: quantity,
                            price: $("[id^=price_item]")
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

                                    $("#TotalCost").text(
                                            // round the results to 2 digits
                                            sum.toFixed(2)
                                            );
                                });




                    }

            //CC Remove
            $('#cost_center .remove').click(function() {

                $(this).parent().parent().remove();
                var total = SummationOfAColumn('cost_center', 6);
                $('#TotalCost').text(total);

            });


            $('#productTab .remove').click(function() {

                $(this).parent().parent().remove();
                var total = SummationOfAColumn('productTab', 5);
                $('#ProductGrantTotal').text(parseFloat(total).toFixed(2));

            });



            $('#addTableTr').click(function() {

                var tr = $('#productTab tbody>tr:last').clone(true);
                var td = tr.find('td:first');
                var sl = parseInt(td.text());
                td.text(sl + 1);
                //$('.search', tr).autocomplete(autocomp_opt);
                //tr.insertAfter('#works tbody>tr:last').find('input, select').attr('class', 'add').val('');




                var newtr = $('<tr>\n\
                <td>' + i + '</td>\n\
                <td><input type="text" style="width:100%" class="Product required" name="item[]" /></td>\n\
                <td><input type="text" style="width:60%" class="" size="30" name="quantity[]" /></td>\n\
                <td><input type="text" style="width:100%" class="" size="30" name="price[]" /></td>\n\
                <td id="TotalPrice" align="right">00.00 </td>\n\
                <td ><input style="width:100%" name="remark[]" type="text" /></td>\n\
                <td><div class="remove">Remove</div></td>\n\
                </tr>');
                //var newtr = tr;
                $('.Product', newtr).autocomplete(autocomp_opt);
                $tableBody.append(newtr);
                i++;
            });



        });




function ProductUnit(obj) {
    var product_id, itemrow, result;
    product_id = obj.val();
    //console.log(product_id);
    itemrow = obj.closest('tr');
    $.ajax({
        type: "GET",
        url: 'AjaxProductUnit.php?product_id=' + product_id,
        success: function(data) {
            result = eval('(' + data + ')');
            itemrow.find('.AjaxProductUnit').text(result.UNIT_TYPE_NAME);
        }
    });
}


//emp Info
function EmpInfo(obj) {
    var cardno, result, itemrow;
    cardno = obj.val();

    itemrow = obj.closest('tr');

    $.ajax({
        url: "ajax_employee_info.php?card_no=" + cardno,
        type: "GET",
        contentType: "application/json",
        dataType: "text",
        success: function(data) {
            result = eval('(' + data + ')');
            itemrow.find('#EmpName').text(result.empMame);
            itemrow.find('#designation').val(result.DESIGNATION_ID);
            itemrow.find('#DesignationName').text(result.DESIGNATION_NAME);


        }
    });
}

function SummationOfAColumn(TableID, ColumnNumber) {
    var Summation = 0;
    var rows = $("#" + TableID + " tr:gt(0)");
    rows.children("td:nth-child(" + ColumnNumber + ")").each(function() {
        //console.log($(this).text());

        var EachRow = $(this).text() * 1.0;
        Summation += EachRow;

    });
    return Summation;
//$('#'+GrandId).text(Summation); 
}

function attachFile() {
    var $table = $("#attachment_tab");
    var $tableBody = $("tbody", $table);
    var countTr = $('#attachment_tab tbody tr').length;
    var sl = countTr + 1;


    var newtr = $('<tr>\n\
                <td>' + sl + '</td>\n\
                <td><div class="float-left" style="width: 400px;"><input type="text" name="AttachmentDetails[]" style="width: 400px;"/></div>\n\
                    <div class="float-left"style="width: 200px;"></div></td>\n\
                <td><div onClick="$(this).parent().parent().remove();">Remove</div></td>\n\
                </tr>');
    //var newtr = tr;
    $tableBody.append(newtr);
    attachComboGrid();
    sl++;
}


function attachComboGrid() {
    $('.attach').combogrid({
        panelWidth: 410,
        idField: 'FILE_ATTACH_LIST_ID',
        textField: 'ATTACH_TITTLE',
        url: 'attache_memo_archive_get.php',
        columns: [[
                {field: 'ATTACH_TITTLE', title: 'Attach Title', width: 200},
                {field: 'ATTACH_FILE_PATH', title: 'Path', width: 200}
            ]],
        fitColumns: true

    });


}

function CheckPRAmount(TableID) {
    var ProductTotal, CCTotal;
    CCTotal = parseFloat($('#TotalCost').text());
    ProductTotal = parseFloat($('#ProductGrantTotal').text()).toFixed(2);
    //alert(ProductTotal);
    if (ProductTotal == CCTotal && ProductTotal != 0.00)
    {
        alert('You cannot add more CC.');
        return;
    }
    RemoveTableTr(TableID);


}


function RemoveTableTr(TableID) {
    var tr = $('#' + TableID + ' tbody>tr:last').clone(true);
    var td = tr.find('td:first');
    var sl = parseInt(td.text());
    td.text(sl + 1);
    tr.insertAfter('#' + TableID + ' tbody>tr:last').find('input, select, label').val('');
}









//------------------



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



