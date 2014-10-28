function AddABoq(TableID) {
    var tr = $('#' + TableID + ' tbody>tr:last').clone(true);
    var td = tr.find('td:first');
    var sl = parseInt(td.text());
    td.text(sl + 1 + '.');
    tr.insertAfter('#' + TableID + ' tbody>tr:last').find('input, select').attr('class', 'add').val('');
}

function EmployeeInfo(obj) {
    var Card_no, result, itemrow;
    Card_no = obj.val();

    itemrow = obj.closest('tr');
    $('#loder').show();
    $.ajax({
        url: "ajax_employee.php?card_no=" + Card_no,
        type: "GET",
        contentType: "application/json",
        dataType: "text",
        success: function(data) {
            result = JSON.parse(data);
            itemrow.find('#employee_details').html(result.empName);
            itemrow.find('#employee_id').val(result.EMPLOYEE_ID);
            itemrow.find('#designationId').val(result.DESIGNATION_ID);
            $('#loder').hide();
        }
    });
}

function removeStackHolder(requisition_id, module, mode) {

    var Requisition_id = requisition_id;
    alert(Requisition_id);
    var Module = module;
    var Mode = mode;
    $.messager.confirm('Confirm', 'Are you sure you want to destroy this user?', function(r) {
        if (r) {
            alert(Requisition_id, Module, Mode);
        }
    });


}

function DeleteStackHolder(Requisition_id, Module, Mode) {
    var Requisition_id1 = Requisition_id;
    var Module1 = Module;
    var Mode1 = Mode;
    $.ajax({
        type: "GET",
        url: 'stack_holder_delete.php?&mode=delete&search_id=' + Requisition_id1,
        success: function(data) { //alert (data);
            //console.log(data);
            //window.location.href = 'index.php?requisition_id='+ Requisition_id1;
            window.location.href = 'stack_holder_new.php?mode=' + Mode1 + '&module=' + Module1 + '&requisition_id=' + Requisition_id1;

        }
    });

}