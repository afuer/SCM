    
var employeeId;

$(document).ready(function(){
    employeeId= $('#employeeId').val();


    
});

function editEmployeePersonalInfo(){
    $('#empPersonal').load('employee_personal_edit.php?employeeId='+ employeeId);
}

function editEmployeeOfficeInfo(){

    $('#empOfficeInfo').load('employee_office_edit.php?employeeId='+ employeeId + 'tab=office');

}

function editEmployeeBankAccInfo(){
    $('#empBankAccount').load('employee_bank_account_edit.php?employeeId='+ employeeId);
}

function editEmployeePic(){
    $('#empPicture').load('employee_picture_edit.php?employeeId='+ employeeId);
}



function editEmployeeAddress(){
    
    //$('#tab_personal').tabs('select', '#empAddress'); 
    // $('#empPersonal').load('employee_address_edit.php?employeeId='+ employeeId);
    $('#empAddress').load('employee_address_edit.php?employeeId='+ employeeId);
   
}

function editEmployeeFamilyInfo(){
    $('#empPersonal').load('employee_family_info_edit.php?employeeId='+ employeeId);
}

function editEmployeeNomineeInfo(){
    $('#empPersonal').load('employee_nominee_info_edit.php?employeeId='+ employeeId);
}

function editEmployeeEducationInfo(){ 
    $('#empPersonal').load('employee_education_info_edit.php?employeeId='+ employeeId);
}



function careerInfo() { 
    location.replace('../employee_career/index.php?employeeId=' + employeeId);
}

function Qualification() {
    location.replace('../employee_qualification/index.php?employeeId=' + employeeId);
}







function saveEmployee() {
    
    $('#emPerInfoEdit').form('submit', {
        url: 'employee_save.php?employeeId='+ employeeId,
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function saveEmployeeoffice() {
    
    $('#emOfficeInfoEdit').form('submit', {
        url: 'employee_office_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //alert(result);
            var result = eval('(' + result + ')');
            if (result.success) { 
                //$('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                //window.location.href = 'index.php';
                $('#empOfficeInfo').load('employee_office_info.php?employeeId='+ employeeId);
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}


function saveEmployeeoBankAccount() {
  
    $('#emBankAccountEdit').form('submit', {
        url: 'employee_bank_account_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            // alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empBankAccount').load('employee_bank_account.php?employeeId='+ employeeId);
            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}



function saveLogin() {
  
    $('#emLoginEdit').form('submit', {
        url: 'employee_login_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) { //alert(data);
            //alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                //$('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empLogin').load('employee_login.php?employeeId='+ employeeId+'&mess=done');

            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
    
}



function saveEmployeeoAddress() {
  
    $('#emBankAddressEdit').form('submit', {
        url: 'employee_address_save.php', 
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            //alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {

                // $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empAddress').load('employee_address.php?employeeId='+ employeeId);

            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}



/* function saveEmployeeoFamilyInfo() {
  
    $('#emFamilyInfoEdit').form('submit', {
        url: 'employee_family_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {

                $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empFamily').load('employee_family_info.php?employeeId='+ employeeId);

            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function saveEmployeeoNomineeInfo() {
  
    $('#emNomineeEdit').form('submit', {
        url: 'employee_nominee_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empNominee').load('employee_nominee_info.php?employeeId='+ employeeId);

            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

function saveEmployeeoEducationInfo() {
  
    $('#emEducationEdit').form('submit', {
        url: 'employee_education_save.php',
        onSubmit: function() {
            return $(this).form('validate');
        },
        success: function(result) {
            alert(result);
            var result = eval('(' + result + ')');
            if (result.success) {
                $('#empPersonal').load('employee_personal.php?employeeId='+ employeeId);
                $('#empEducation').load('employee_education_info.php?employeeId='+ employeeId);

            } else {
                $.messager.show({
                    title: 'Error',
                    msg: result.msg
                });
            }
        }
    });
}

*/

function password(){
    var UserPass = $('#USER_PASS').val();
    var RePass = $('#RE_PASSWORD').val();
    
    if(UserPass != RePass ){
        alert('Please Match Confarm Password');
        
    }
    
}


$(document).ready(function() {

    $('input:text[name=LINE_MANAGER_ID]').live('change', function() {  
        var LineManager = $('input:text[name=LINE_MANAGER_ID]').val();
        //alert(Supervisor);
        $.ajax({
            type: "GET",
            url: 'ajax_line_manager.php?val='+LineManager,
            success: function(data){
                console.log(data);
                     
                $('#AjaxLineManager').html(data);
                $('#lineManager').html(data);
                                
            }
        });
   
    }); 
    

    
    $('#IS_RELIEVER').live('change', function(){
        if($(this).is(':checked')){
            $('#Reliever').show();
            $('#AjaxReliever').show();
        } else {
           $('#Reliever').hide();
           $('#AjaxReliever').hide();
           
        }
    });
    
        $('#RELIEVER_EMP_ID').live('change', function() {  
        var Reliever_id = $('#RELIEVER_EMP_ID').val();
        //alert(Supervisor);
        $.ajax({
            type: "GET",
            url: 'ajax_reliever.php?val='+Reliever_id,
            success: function(data){
                console.log(data);
                     
                $('#AjaxReliever').html(data);
               
                                
            }
        });
   
    }); 
    
     
    
    $('#EMPLOYEE_TYPE_IDID').live('change', function() { 
          
        
        var EmpType = $('#EMPLOYEE_TYPE_IDID').val();
        //alert(Supervisor);
        $.ajax({
            type: "GET",
            url: 'ajax_supplier.php?val='+EmpType,
            success: function(data){
                console.log(data);
                     
                $('#outerEmployee').html(data);
                                
            }
        });
   
    });
    
    $('#SUPPLIER_IDID').live('change', function() { 
          
        
        var SupplierId = $('#SUPPLIER_IDID').val();
        $('#loder').show(); 
        //alert(Supervisor);
        $.ajax({
            type: "GET",
            url: 'ajax_supplierHeading.php?val='+SupplierId,
            success: function(data){
                console.log(data);
                     
                $('#supplierHeading').html(data);
                $('#loder').hide();             
            }
        });
   
    });
    
    
    
    $('#RE_PASSWORD').live('change', function() {
        password();
          
    });
    
    
});