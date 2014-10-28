<?php
include_once '../lib/DbManager.php';
include '../body/header.php';

$data = array(array('aaaaaaaaaa'), array('b'), array('c'));
?>

<style type="text/css">
    #ff label{
        display:block;
        width:100px;
    }
</style>
<script type="text/javascript">

    $(document).ready(function() {
        //$('#rajibID').attr('required','true');
        
    });

    $(function() {

$('#rajibID').attr('required','true');
        $('#ff').form({
            url: 'form3_proc.php',
            onSubmit: function() {
                return $(this).form('validate');
            },
            success: function(data) {
                $.messager.alert('Info', data, 'info');
            }
        });
    });
</script>
</head>
<body>
    <div style="width:230px;background:#fafafa;padding:10px;">
        <div style="padding:3px 2px;border-bottom:1px solid #ccc">Form Validation</div>
        <form id="ff" method="post">
            <div>
                <label for="name">Name:</label>
                <?php comboBox('rajib', $data, '', TRUE); ?>

            </div>
            <div>
                <label for="email">Email:</label>
                <input class="easyui-validatebox" type="text" name="email" required="true" validType="email"></input>
            </div>
            <div>
                <label for="subject">Subject:</label>
                <input class="easyui-validatebox" type="text" name="subject" required="true"></input>
            </div>
            <div>
                <label for="message">Message:</label>
                <textarea name="message" style="height:60px;"></textarea>
            </div>
            <div>
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>




    <?php include '../body/footer.php'; ?>