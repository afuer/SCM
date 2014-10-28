<?php
include '../lib/DbManager.php';
include '../body/header.php';

// The location of the PDF file on the server.
//$filename = "../documents/PR/GettingStarted.pdf";
// Let the browser know that a PDF file is coming.
//header("Content-type: application/pdf");
//header("Content-Length: " . filesize($filename));
// Send the file to the browser.
//readfile($filename);
//exit;
?>

<!--<script type="text/javascript">

    function playSound(obj) {
        var source = obj.attr('id');
        //console.log(source);

        //$('#' + source).attr('disabled','disabled');
        $('#' + source).delay(800);
        $('#' + source)[0].play();
        //$('#' + source).removeAttr('disabled');
    }

</script>-->

<br/><br/><br/><br/><br/><br/>




<div style="text-align: center;">
    <h2 align="center">Welcome to City Bank</h2><br/>
</div>

<?php include '../body/footer.php'; ?>
