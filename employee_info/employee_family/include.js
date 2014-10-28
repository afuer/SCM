    
var employeeId;

$(document).ready(function(){
    employeeId= $('#employeeId').val();
    
    
});



function careerInfo() { 
        location.replace('../employee_career/index.php?employeeId=' + employeeId);
}

function professionalQualification() { 
        location.replace('employee_professional_qualification.php?employeeId=' + employeeId);
}



$(document).ready(function() {  
    $('#employeeHeader').load('../employee_info/employee_header.php?employeeId='+ employeeId);
   
});

   