$(document).ready(function(){
    addCombo();
    addComboForCC();

    $('#productGrid .remove').click(function() {
        /*
        var val = $(this).attr('id');
        //console.log(val);
        $.ajax({
            type: "POST",
            url: 'ajax_remove_product_by_id.php?val=' + val,
            success: function(data) {
            }
        }); */
        $(this).parent().parent().remove();
        //RecalcProduct();

    });
    
    $('select[name^=item]').change(function(){ 
        var item;
        item =$('select[name^=item]').val();
        $.ajax({
            type: "GET",
            url: 'AjaxProductUnit.php?item='+item,
            success: function(data){
                                
                $('.AjaxProductUnit').html(data);
               
                                
            }
        });    
    });
     
    
    $('.quantity').change(function(){
        RecalcProduct();
    });
            

    $('.price').change(function(){
        RecalcProduct();
    });
    
    
        
    function RecalcProduct(){
        $("[id^=TotalPrice]").calc(
            // the equation to use for the calculation
            "qty * price",
            // define the variables used in the equation, these can be a jQuery object
            {
                qty: $("input[name^=quantity]"),
                price: $("[id^=price_product]")
            },
            // define the formatting callback, the results of the calculation are passed to this function
            function (s){
                // return the number as a dollar amount
                return s.toFixed(2);
            },
            // define the finish callback, this runs after the calculation has been complete
            function ($this){
                // sum the total of the $("[id^=total_item]") selector
                var  sum = $this.sum();
				
                $("#ProductGrantTotal").text(
                    // round the results to 2 digits
                    sum.toFixed(2)
                    );
            });
    }
    
   
    
    ///END  ADD PRODUCT ////
        
    //ADD CostCenter
    var i=1, TotalCost =0, j=1, k=1, account_type, WorkflowType; 
    
    $('.CostcenterPercent').attr('disabled', 'disable');
    
    $('.account_type').change(function(){
        account_type=$(this).val();
            
        if(account_type==0){
            $('.CostcenterPercent').attr('disabled', 'disable');
        }else{
            $('.CostcenterPercent').removeAttr('disabled');
        }

    });
                
    
    //// ccc
    
    $('.CostcenterPercent').change(function(){

        var CostcenterPercent, CostcenterAmount, budget;
        budget=$('#ProductGrantTotal').text();
        CostcenterPercent=$(this).val();
        
        CostcenterAmount=parseFloat((budget*CostcenterPercent)/100).toFixed(2);
                   
        $(this).parents('tr:first').find('input.CostcenterAmount').val(CostcenterAmount);

        recalc(1);
                
    });
            
    $('.CostcenterAmount').change(function(){


        var CostcenterPercent, budget;
        budget=$('#ProductGrantTotal').text();
        CostcenterPercent=$(this).val();
        CostcenterAmount=parseFloat((budget*CostcenterPercent)/100).toFixed(2);
        recalc(1);
        
        var ProductTotal, CCTotal;
        CCTotal =parseFloat($('#TotalCost').text());
        ProductTotal = parseFloat($('#ProductGrantTotal').text());
        if(ProductTotal < CCTotal )
        {
            var differ=CCTotal-ProductTotal;
            alert('Cost center Total is not allowed to greater than Product Total');
            var CurrentVal=$(this).val();
            var ActualAmount=CurrentVal-differ;
            $(this).val(ActualAmount);
        }
        
        
        
        
        
                
    });

    var sum; 
    function recalc(quantity){
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
            function (s){
                // return the number as a dollar amount
                return s.toFixed(2);
            },
            // define the finish callback, this runs after the calculation has been complete
            function ($this){
                // sum the total of the $("[id^=total_item]") selector
                var  sum = $this.sum();
				
                $("#TotalCost").text(
                    // round the results to 2 digits
                    sum.toFixed(2)
                    );
            });
            
     
                           
                        
    }
    
    //CC Remove
    $('#cost_center .remove').click(function(){

        $(this).parent().parent().remove();
        var total=SummationOfAColumn('cost_center', 6);
        $('#TotalCost').text(total);
            
    });
    
    
     $('#memo_info_ref .remove').click(function(){

        $(this).parent().parent().remove();
        var total=SummationOfAColumn('cost_center', 6);
        $('#TotalCost').text(total);
            
    });
    
    
     $('#productTab .remove').click(function(){
            
        $(this).parent().parent().remove();
        var total=SummationOfAColumn('productTab', 5);
        $('#ProductGrantTotal').text(total);
            
    });
 
   $('#a .remove').click(function(){
            
        $(this).parent().parent().remove();
        var total=SummationOfAColumn('productTab', 5);
        $('#ProductGrantTotal').text(total);
            
    });
    
    
                
    $('input:radio[name=Workflow]').live('change', function() {  
        WorkflowType = $('input:radio[name=Workflow]:checked').val();
                    
        if(WorkflowType==1){
            $.ajax({
                type: "GET",
                url: 'AjaxManualWorkFlow.php',
                success: function(data){
                                
                    $('#AjaxManualWorkFlow').html(data);
                                
                }
            });
            $('#WorkflowDefault').hide();
            $('#WorkflowTab_defult').hide();
               
        }else{
            $('#WorkflowDefault').show();
            $('#AjaxManualWorkFlow').html('');
                
        }    
    });


        
        
    $('.cardno').change(function(){
            
        var cardn;
        cardno=$(this).val();
        var txtEmpName = $(this).parents('tr').find('.EmpName');
        var txtEmail = $(this).parents('tr').find('.EmpEmail');
        var txtEmpCell = $(this).parents('tr').find('.EmpCell');
                        
        $.ajax({
            url: "ajax_employee_info.php?card_no="+cardno,
            type: "GET",
            contentType: "application/json",
            dataType: "text",
            success: function (data) {
                    
                //$("#event_list").html(data);
                result = eval('('+data+')');
               
                txtEmpName.text(result.FULL_NAME);
                txtEmail.text(result.EMAIL);
                txtEmpCell.text(result.EMAIL);
            }
        });
                
    });
        
}); 



function SummationOfAColumn(TableID,ColumnNumber){
    var Summation = 0;
    var rows = $("#"+TableID+" tr:gt(0)");
    rows.children("td:nth-child("+ColumnNumber+")").each(function() {
        //console.log($(this).text());
        
        var EachRow=$(this).text()*1.0;
        Summation +=EachRow ;
        
    });
    return Summation;
//$('#'+GrandId).text(Summation); 
}

   
function RemoveTableTr(TableID){
    var tr=$('#'+TableID+' tbody>tr:last').clone(true);
    var td = tr.find('td:first');
    var sl = parseInt(td.text());
    td.text(sl + 1);
    tr.insertAfter('#'+TableID+' tbody>tr:last').find('input, select').attr('class', 'add').val('');
}


function AddAsset(TableID){
    var tr=$('#'+TableID+' tbody>tr:last').clone(true);
    var td = tr.find('td:first');
    var sl = parseInt(td.text());
    td.text(sl + 1+'.');
    tr.insertAfter('#'+TableID+' tbody>tr:last').find('input, select').attr('class', 'add').val('');
}



function addCombo() {
            //var requisition_type_id = $('#requisition_type_id').val();
            //var processDeptId = $('#processDeptId').val();
            var $table = $("#productGrid");
            var $tableBody = $("tbody", $table);
            var countTr = $('#productGrid tbody tr').length;
            var sl = countTr + 1;

            var newtr = $('<tr>\n\
        <td>' + sl + '</td>\n\
            <td><input type="text" name="productId[]" style="width:500px" class="product required" /></td>\n\\n\
            <td align="center"><a href="#" class="easyui-linkbutton" onClick="alert("AAA");>View</a></td>\
            <td><div class="remove" align="right" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div></td>\n\
        </tr>');

            $tableBody.append(newtr);
            $('.product', newtr).combogrid({
                panelWidth: 600,
                required: true,
                url: 'ComboRefInfo.php',
                idField: 'MEMO_REF',
                textField: 'MEMO_REF',
                mode: 'remote',
                fitColumns: true,
                columns: [[
                        {field: 'MEMO_REF', title: 'Memo Ref', width:50},
                        {field: 'MEMO_SUBJECT', title: 'Memo Subject', width:50},
                        {field: 'MEMO_DATE', title: 'Memo Date', width:50},
                        {field: 'MEMO_TYPE', title: 'Memo Type', width:50},
                        {field: 'APPROVED_AMOUNT', title: 'Approved Amount', width:50}
                    ]]
            });
        }
        
        
        
function addComboForCC() {
            //var requisition_type_id = $('#requisition_type_id').val();
            //var processDeptId = $('#processDeptId').val();
            var $table = $("#productGrid1");
            var $tableBody = $("tbody", $table);
            var countTr = $('#productGrid1 tbody tr').length;
            var sl = countTr + 1;

            var newtr = $('<tr>\n\
        <td>' + sl + '</td>\n\
            <td><input type="text" name="costCenterId[]" style="width:500px" class="product1 required" /></td>\n\
            <td>\n\
        <div class="remove" align="center" onClick="$(this).parent().parent().remove();"><img src="../public/images/delete.png"/></div>\n\
        </td>\n\
        </tr>');

            $tableBody.append(newtr);
            $('.product1', newtr).combogrid({
                panelWidth: 600,
                required: true,
                url: 'comboccinfo.php',
                idField: 'COST_CENTER_ID',
                textField: 'COST_CENTER_NAME',
                mode: 'remote',
                fitColumns: true,
                columns: [[
                        {field: 'COST_CENTER_CODE', title: 'CC Code', width:50},
                        {field: 'COST_CENTER_NAME', title: 'CC Name', width:50},
                        {field: 'DIVISION_NAME', title: 'Division Name', width:50}
                    ]]
            });
        }
        
       
function getValue(){  
            var val = $('#cmGrid').combogrid('getValue');  
            alert(val);  
        }  