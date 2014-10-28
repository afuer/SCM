$(document).ready(function() {

    $('#DIVISION_ID').combobox({
        url: '../division/division_get_combo.php',
        valueField: 'DIVISION_ID',
        textField: 'DIVISION_NAME'
    });

});

